<?php

declare(strict_types=1);

namespace App\Service\CommissionRulesChain\WithdrawChain;

use App\Collection\TransactionsCollection;
use App\Enum\AccountTypeEnum;
use App\Enum\CurrencyEnum;
use App\Enum\TransactionTypeEnum;
use App\Service\CommissionRulesChain\AbstractRule;
use App\Service\CurrencyExchangeServiceInterface;
use App\Service\MoneyCalculator\MoneyCalculatorInterface;
use App\VO\Money;

class PrivateAccountWithdrawRule extends AbstractRule
{
    private int $freeTransactionPerWeek;
    private float $commissionPercent;
    private int $freeSum;

    public function __construct(protected MoneyCalculatorInterface $moneyCalculator, private CurrencyExchangeServiceInterface $currencyExchangeService)
    {
        $this->freeTransactionPerWeek = (int)$_ENV['WITHDRAW_PRIVATE_ACCOUNT_FREE_TRANSACTIONS_PER_WEEK'];
        $this->commissionPercent = (float)$_ENV['WITHDRAW_PRIVATE_ACCOUNT_COMMISSION_PERCENT'];
        $this->freeSum = (int)$_ENV['WITHDRAW_PRIVATE_ACCOUNT_FREE_SUM'];

        parent::__construct($moneyCalculator);
    }

    protected function getLastUserTransactionCommission(TransactionsCollection $userHistoryUpToCurrentTransaction): float
    {
        $allUserWithdrawsForLastTransactionWeek = $this->getAllUserWithdrawsForLastTransactionWeek($userHistoryUpToCurrentTransaction);

        if ($allUserWithdrawsForLastTransactionWeek->getSize() > $this->freeTransactionPerWeek) {
            return $this->moneyCalculator
                ->getPercent(
                    $userHistoryUpToCurrentTransaction->getLastTransaction()->getMoney(),
                    $this->commissionPercent
                )
                ->getValue();
        }

        $lastTransactionMoney = $userHistoryUpToCurrentTransaction->getLastTransaction()->getMoney();

        $lastTransactionMoneyInDefaultCurrency = $this->currencyExchangeService->convertMoneyToDefaultCurrency(
            $userHistoryUpToCurrentTransaction->getLastTransaction()->getMoney()
        );

        $allUserWithdrawsForLastTransactionWeekMoneyInDefaultCurrency = $this->getAllUserWithdrawsForLastTransactionWeekMoneyInDefaultCurrency(
            $allUserWithdrawsForLastTransactionWeek
        );

        $remainingWithoutCommissionMoneyInDefaultCurrency = $this->moneyCalculator->minus(
            (new Money($this->freeSum, CurrencyEnum::getDefaultCurrency())),
            $this->moneyCalculator->minus(
                $allUserWithdrawsForLastTransactionWeekMoneyInDefaultCurrency,
                $lastTransactionMoneyInDefaultCurrency
            )
        );

        $underCommissionMoneyInDefaultCurrency = $this->moneyCalculator->minus(
            $lastTransactionMoneyInDefaultCurrency,
            $remainingWithoutCommissionMoneyInDefaultCurrency
        );

        return $this->moneyCalculator
            ->getPercent(
                $this->currencyExchangeService->convertMoney($underCommissionMoneyInDefaultCurrency, $lastTransactionMoney->getCurrency()),
                $this->commissionPercent
            )
            ->getValue();
    }

    protected function isAppropriateRule(TransactionsCollection $userHistoryUpToCurrentTransaction): bool
    {
        return (
            $userHistoryUpToCurrentTransaction->getLastTransaction()->getTransactionTypeEnum() == TransactionTypeEnum::Withdraw
            && $userHistoryUpToCurrentTransaction->getLastTransaction()->getAccountTypeEnum() == AccountTypeEnum::Private
        );
    }

    private function getAllUserWithdrawsForLastTransactionWeekMoneyInDefaultCurrency(TransactionsCollection $allUserWithdrawsForLastTransactionWeek): Money
    {
        $allUserWithdrawsForLastTransactionWeekMoneyInDefaultCurrency = new Money(0.0, CurrencyEnum::getDefaultCurrency());

        foreach ($allUserWithdrawsForLastTransactionWeek->getTransactions() as $transactionDTO) {
            $allUserWithdrawsForLastTransactionWeekMoneyInDefaultCurrency = $this->moneyCalculator->add(
                $allUserWithdrawsForLastTransactionWeekMoneyInDefaultCurrency,
                $this->currencyExchangeService->convertMoneyToDefaultCurrency($transactionDTO->getMoney())
            );
        }

        return $allUserWithdrawsForLastTransactionWeekMoneyInDefaultCurrency;
    }

    private function getAllUserWithdrawsForLastTransactionWeek(TransactionsCollection $userTransactionsCollection): TransactionsCollection
    {
        $allUserWithdrawsForLastTransactionWeek = new TransactionsCollection();

        $lastTransactionWeekStartTimestamp = strtotime(
            'last monday',
            strtotime($userTransactionsCollection->getLastTransaction()->getDate()->format('Y-m-d'))
        );

        foreach ($userTransactionsCollection->getTransactions() as $userTransactionDTO) {
            if (
                $userTransactionDTO->getTransactionTypeEnum() == TransactionTypeEnum::Withdraw
                && $userTransactionDTO->getDate()->getTimestamp() >= $lastTransactionWeekStartTimestamp
            ) {
                $allUserWithdrawsForLastTransactionWeek->addTransaction($userTransactionDTO);
            }
        }

        return $allUserWithdrawsForLastTransactionWeek;
    }
}
