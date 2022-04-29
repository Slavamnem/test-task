<?php

declare(strict_types=1);

namespace App\Service\CommissionRulesChain\DepositChain;

use App\Collection\TransactionsCollection;
use App\Enum\TransactionTypeEnum;
use App\Service\CommissionRulesChain\AbstractRule;
use App\Service\CurrencyExchangeServiceInterface;

class DefaultDepositRule extends AbstractRule
{
    private float $commissionPercent;

    public function __construct(protected CurrencyExchangeServiceInterface $currencyExchangeService)
    {
        $this->commissionPercent = (float)$_ENV['DEPOSIT_COMMISSION_PERCENT'];

        parent::__construct($this->currencyExchangeService);
    }

    protected function getLastUserTransactionCommission(TransactionsCollection $userHistoryUpToCurrentTransaction): float
    {
        return $userHistoryUpToCurrentTransaction
            ->getLastTransaction()
            ->getMoney()
            ->multiply($this->commissionPercent / 100)
            ->getValue();
    }

    protected function isAppropriateRule(TransactionsCollection $userHistoryUpToCurrentTransaction): bool
    {
        return $userHistoryUpToCurrentTransaction->getLastTransaction()->getTransactionTypeEnum() == TransactionTypeEnum::Deposit;
    }
}
