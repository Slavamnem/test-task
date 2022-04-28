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

    /**
     * @param TransactionsCollection $userTransactionsCollection
     * @return float
     * @throws \Exception
     */
    protected function getLastUserTransactionCommission(TransactionsCollection $userTransactionsCollection): float
    {
        $allUserWithdrawsForLastTransactionWeek = $this->getAllUserWithdrawsForLastTransactionWeek($userTransactionsCollection);

        if ($allUserWithdrawsForLastTransactionWeek->getSize() > self::FREE_TRANSACTIONS_PER_WEEK) {
            return $userTransactionsCollection->getLastTransaction()->getMoney()->multiply(self::COMMISSION_PERCENT / 100)->getValue();
        }

        $lastTransactionMoney = $userTransactionsCollection->getLastTransaction()->getMoney();

        $lastTransactionMoneyInDefaultCurrency = $this->currencyExchangeService->convertMoneyToDefaultCurrency($userTransactionsCollection->getLastTransaction()->getMoney());
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

    /**
     * @param TransactionsCollection $userTransactionsCollection
     * @return bool
     */
    protected function isAppropriateRule(TransactionsCollection $userTransactionsCollection): bool
    {
        return (
            $userTransactionsCollection->getLastTransaction()->getTransactionType()->isEqual(TransactionTypeEnum::WITHDRAW()) &&
            $userTransactionsCollection->getLastTransaction()->getAccountType()->isEqual(AccountTypeEnum::PRIVATE())
        );
    }

    /**
     * @param TransactionsCollection $allUserWithdrawsForLastTransactionWeek
     * @return Money
     * @throws \Exception
     */
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

    /**
     * @param TransactionsCollection $userTransactionsCollection
     * @return TransactionsCollection
     * @throws \Exception
     */
    private function getAllUserWithdrawsForLastTransactionWeek(TransactionsCollection $userTransactionsCollection): TransactionsCollection
    {
        $allUserWithdrawsForLastTransactionWeek = new TransactionsCollection();

        $lastTransactionWeekStartTimestamp = strtotime('last monday', strtotime($userTransactionsCollection->getLastTransaction()->getDate()->format('Y-m-d')));

        foreach ($userTransactionsCollection->getTransactions() as $userTransactionDTO) {
            if (
                $userTransactionDTO->getTransactionType()->isEqual(TransactionTypeEnum::WITHDRAW()) &&
                $userTransactionDTO->getDate()->getTimestamp() >= $lastTransactionWeekStartTimestamp)
            {
                $allUserWithdrawsForLastTransactionWeek->addTransaction($userTransactionDTO);
            }
        }

        return $allUserWithdrawsForLastTransactionWeek;
    }
}
