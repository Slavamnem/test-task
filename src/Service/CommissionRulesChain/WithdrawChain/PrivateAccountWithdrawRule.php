<?php declare(strict_types=1);

namespace App\Service\CommissionRulesChain\WithdrawChain;

use App\Collection\TransactionsCollection;
use App\Enum\AccountTypeEnum;
use App\Enum\CurrencyEnum;
use App\Enum\TransactionTypeEnum;
use App\Service\CommissionRulesChain\AbstractRule;
use App\VO\Money;

class PrivateAccountWithdrawRule extends AbstractRule
{
    private const FREE_TRANSACTIONS_PER_WEEK = 3;
    private const COMMISSION_PERCENT = 0.3;
    private const FREE_SUM = 1000;

    protected function getLastUserTransactionCommission(TransactionsCollection $userHistoryUpToCurrentTransaction): float
    {
        $allUserWithdrawsForLastTransactionWeek = $this->getAllUserWithdrawsForLastTransactionWeek($userHistoryUpToCurrentTransaction);

        if ($allUserWithdrawsForLastTransactionWeek->getSize() > self::FREE_TRANSACTIONS_PER_WEEK) {
            return $userHistoryUpToCurrentTransaction->getLastTransaction()->getMoney()->multiply(self::COMMISSION_PERCENT / 100)->getValue();
        }

        $lastTransactionMoney = $userHistoryUpToCurrentTransaction->getLastTransaction()->getMoney();

        $lastTransactionMoneyInDefaultCurrency = $this->currencyExchangeService->convertMoneyToDefaultCurrency($userHistoryUpToCurrentTransaction->getLastTransaction()->getMoney());
        $allUserWithdrawsForLastTransactionWeekMoneyInDefaultCurrency = $this->getAllUserWithdrawsForLastTransactionWeekMoneyInDefaultCurrency($allUserWithdrawsForLastTransactionWeek);
        $remainingWithoutCommissionMoneyInDefaultCurrency = (new Money(self::FREE_SUM, CurrencyEnum::getDefaultCurrency()))->minus(
                $allUserWithdrawsForLastTransactionWeekMoneyInDefaultCurrency->minus($lastTransactionMoneyInDefaultCurrency)
            );
        $underCommissionMoneyInDefaultCurrency = $lastTransactionMoneyInDefaultCurrency->minus($remainingWithoutCommissionMoneyInDefaultCurrency);

        return $this->currencyExchangeService
            ->convertMoney($underCommissionMoneyInDefaultCurrency, $lastTransactionMoney->getCurrency())
            ->multiply(self::COMMISSION_PERCENT / 100)
            ->getValue();
    }

    protected function isAppropriateRule(TransactionsCollection $userHistoryUpToCurrentTransaction): bool
    {
        return (
            $userHistoryUpToCurrentTransaction->getLastTransaction()->getTransactionTypeEnum() == TransactionTypeEnum::Withdraw &&
            $userHistoryUpToCurrentTransaction->getLastTransaction()->getAccountTypeEnum() == AccountTypeEnum::Private
        );
    }

    private function getAllUserWithdrawsForLastTransactionWeekMoneyInDefaultCurrency(TransactionsCollection $allUserWithdrawsForLastTransactionWeek): Money
    {
        $allUserWithdrawsForLastTransactionWeekMoneyInDefaultCurrency = new Money(0.0, CurrencyEnum::getDefaultCurrency());

        foreach ($allUserWithdrawsForLastTransactionWeek->getTransactions() as $transactionDTO) {
            $allUserWithdrawsForLastTransactionWeekMoneyInDefaultCurrency = $allUserWithdrawsForLastTransactionWeekMoneyInDefaultCurrency->add(
                $this->currencyExchangeService->convertMoneyToDefaultCurrency($transactionDTO->getMoney())
            );
        }

        return $allUserWithdrawsForLastTransactionWeekMoneyInDefaultCurrency;
    }

    private function getAllUserWithdrawsForLastTransactionWeek(TransactionsCollection $userTransactionsCollection): TransactionsCollection
    {
        $allUserWithdrawsForLastTransactionWeek = new TransactionsCollection();

        $lastTransactionWeekStartTimestamp = strtotime('last monday', strtotime($userTransactionsCollection->getLastTransaction()->getDate()->format('Y-m-d')));

        foreach ($userTransactionsCollection->getTransactions() as $userTransactionDTO) {
            if (
                $userTransactionDTO->getTransactionTypeEnum() == TransactionTypeEnum::Withdraw &&
                $userTransactionDTO->getDate()->getTimestamp() >= $lastTransactionWeekStartTimestamp)
            {
                $allUserWithdrawsForLastTransactionWeek->addTransaction($userTransactionDTO);
            }
        }

        return $allUserWithdrawsForLastTransactionWeek;
    }
}
