<?php

declare(strict_types=1);

namespace App\Service\CommissionRulesChain\WithdrawChain;

use App\Collection\TransactionsCollection;
use App\Enum\AccountTypeEnum;
use App\Enum\TransactionTypeEnum;
use App\Service\CommissionRulesChain\AbstractRule;
use App\Service\MoneyCalculator\MoneyCalculatorInterface;

class BusinessAccountWithdrawRule extends AbstractRule
{
    private float $commissionPercent;

    public function __construct(protected MoneyCalculatorInterface $moneyCalculator)
    {
        $this->commissionPercent = (float)$_ENV['WITHDRAW_BUSINESS_ACCOUNT_COMMISSION_PERCENT'];

        parent::__construct($moneyCalculator);
    }

    protected function getLastUserTransactionCommission(TransactionsCollection $userHistoryUpToCurrentTransaction): float
    {
        return $this->moneyCalculator
            ->getPercent(
                $userHistoryUpToCurrentTransaction->getLastTransaction()->getMoney(),
                $this->commissionPercent
            )
            ->getValue();
    }

    protected function isAppropriateRule(TransactionsCollection $userHistoryUpToCurrentTransaction): bool
    {
        return (
            $userHistoryUpToCurrentTransaction->getLastTransaction()->getTransactionTypeEnum() == TransactionTypeEnum::Withdraw
            && $userHistoryUpToCurrentTransaction->getLastTransaction()->getAccountTypeEnum() == AccountTypeEnum::Business
        );
    }
}
