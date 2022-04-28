<?php declare(strict_types=1);

namespace App\Service\CommissionRulesChain;

use App\Collection\TransactionsCollection;
use App\Exception\NotFoundAnyCommissionRuleException;
use App\Service\CurrencyExchangeServiceInterface;

abstract class AbstractRule
{
    protected AbstractRule $nextRule;
    protected CurrencyExchangeServiceInterface $currencyExchangeService;

    /**
     * @param CurrencyExchangeServiceInterface $currencyExchangeService
     */
    public function __construct(CurrencyExchangeServiceInterface $currencyExchangeService)
    {
        $this->currencyExchangeService = $currencyExchangeService;
    }

    /**
     * @param TransactionsCollection $userTransactionsCollection
     * @return float
     * @throws \Exception
     */
    public function calculateCommission(TransactionsCollection $userTransactionsCollection): float
    {
        if ($this->isAppropriateRule($userTransactionsCollection)) {
            return $this->getLastUserTransactionCommission($userTransactionsCollection);
        } elseif (!empty($this->nextRule)) {
            return $this->nextRule->calculateCommission($userTransactionsCollection);
        }

        throw new NotFoundAnyCommissionRuleException();
    }

    /**
     * @param AbstractRule $nextRule
     * @return $this
     */
    public function setNextRule(AbstractRule $nextRule): AbstractRule
    {
        $this->nextRule = $nextRule;
        return $this;
    }

    /**
     * @param TransactionsCollection $userTransactionsCollection
     * @return float
     */
    abstract protected function getLastUserTransactionCommission(TransactionsCollection $userTransactionsCollection): float;

    /**
     * @param TransactionsCollection $userTransactionsCollection
     * @return bool
     */
    abstract protected function isAppropriateRule(TransactionsCollection $userTransactionsCollection): bool;
}
