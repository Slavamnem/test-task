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
    public function __construct(
        protected MoneyCalculatorInterface $moneyCalculator,
        private CurrencyExchangeServiceInterface $currencyExchangeService,
        private string $defaultCurrency,
        private int $freeTransactionPerWeek,
        private float $commissionPercent,
        private float $freeSum,
    ) {
        parent::__construct($moneyCalculator);
    }

    protected function getLastUserTransactionCommission(TransactionsCollection $userHistoryUpToCurrentTransaction): Money
    {
        $allUserWithdrawsForLastTransactionWeek = $this->getAllUserWithdrawsForLastTransactionWeek($userHistoryUpToCurrentTransaction);

        if ($allUserWithdrawsForLastTransactionWeek->getSize() > $this->freeTransactionPerWeek) {
            return $this->moneyCalculator->roundUp(
                $this->moneyCalculator->getPercent(
                    $userHistoryUpToCurrentTransaction->getLastTransaction()->getMoney(),
                    $this->commissionPercent
                )
            );
        }

        $lastTransactionMoney = $userHistoryUpToCurrentTransaction->getLastTransaction()->getMoney();

        $lastTransactionMoneyInDefaultCurrency = $this->currencyExchangeService->convertMoneyToDefaultCurrency(
            $userHistoryUpToCurrentTransaction->getLastTransaction()->getMoney()
        );

        $allUserWithdrawsForLastTransactionWeekMoneyInDefaultCurrency = $this
            ->getAllUserWithdrawsForLastTransactionWeekMoneyInDefaultCurrency($allUserWithdrawsForLastTransactionWeek);

        $remainingWithoutCommissionMoneyInDefaultCurrency = $this->moneyCalculator->minus(
            (new Money($this->freeSum, CurrencyEnum::from($this->defaultCurrency))),
            $this->moneyCalculator->minus(
                $allUserWithdrawsForLastTransactionWeekMoneyInDefaultCurrency,
                $lastTransactionMoneyInDefaultCurrency
            )
        );

        $underCommissionMoneyInDefaultCurrency = $this->moneyCalculator->minus(
            $lastTransactionMoneyInDefaultCurrency,
            $remainingWithoutCommissionMoneyInDefaultCurrency
        );

        return $this->moneyCalculator->roundUp(
            $this->moneyCalculator->getPercent(
                $this->currencyExchangeService->convertMoney(
                    $underCommissionMoneyInDefaultCurrency,
                    $lastTransactionMoney->getCurrency()
                ),
                $this->commissionPercent
            )
        );
    }

    protected function isAppropriateRule(TransactionsCollection $userHistoryUpToCurrentTransaction): bool
    {
        return
            $userHistoryUpToCurrentTransaction->getLastTransaction()->getTransactionTypeEnum() === TransactionTypeEnum::Withdraw
            && $userHistoryUpToCurrentTransaction->getLastTransaction()->getAccountTypeEnum() === AccountTypeEnum::Private
        ;
    }

    private function getAllUserWithdrawsForLastTransactionWeekMoneyInDefaultCurrency(
        TransactionsCollection $allUserWithdrawsForLastTransactionWeek
    ): Money {
        $allUserWithdrawsForLastTransactionWeekMoneyInDefaultCurrency = new Money(0.0, CurrencyEnum::from($this->defaultCurrency));

        foreach ($allUserWithdrawsForLastTransactionWeek->getTransactions() as $transactionDTO) {
            $allUserWithdrawsForLastTransactionWeekMoneyInDefaultCurrency = $this->moneyCalculator->add(
                $allUserWithdrawsForLastTransactionWeekMoneyInDefaultCurrency,
                $this->currencyExchangeService->convertMoneyToDefaultCurrency($transactionDTO->getMoney())
            );
        }

        return $allUserWithdrawsForLastTransactionWeekMoneyInDefaultCurrency;
    }

    private function getAllUserWithdrawsForLastTransactionWeek(TransactionsCollection $userHistoryUpToCurrentTransaction): TransactionsCollection
    {
        $allUserWithdrawsForLastTransactionWeek = new TransactionsCollection();

        $lastTransactionWeekStartTimestamp = strtotime(
            'last monday',
            strtotime($userHistoryUpToCurrentTransaction->getLastTransaction()->getDate()->format('Y-m-d'))
        );

        foreach ($userHistoryUpToCurrentTransaction->getTransactions() as $userTransactionDTO) {
            if (
                $userTransactionDTO->getTransactionTypeEnum() === TransactionTypeEnum::Withdraw
                && $userTransactionDTO->getDate()->getTimestamp() >= $lastTransactionWeekStartTimestamp
            ) {
                $allUserWithdrawsForLastTransactionWeek->addTransaction($userTransactionDTO);
            }
        }

        return $allUserWithdrawsForLastTransactionWeek;
    }
}
