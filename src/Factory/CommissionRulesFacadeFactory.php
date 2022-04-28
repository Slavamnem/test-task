<?php declare(strict_types=1);

namespace App\Factory;

use App\Enum\TransactionTypeEnum;
use App\Service\CommissionRulesChain\CommissionRulesChain;
use App\Service\CommissionRulesChain\CommissionRulesFacade;
use App\Service\CommissionRulesChain\CommissionRulesFacadeInterface;
use App\Service\CommissionRulesChain\DepositChain\DefaultDepositRule;
use App\Service\CommissionRulesChain\WithdrawChain\BusinessAccountWithdrawRule;
use App\Service\CommissionRulesChain\WithdrawChain\PrivateAccountWithdrawRule;
use App\Service\CurrencyExchangeServiceInterface;

class CommissionRulesFacadeFactory
{
    private CurrencyExchangeServiceInterface $currencyExchangeService;

    /**
     * @param CurrencyExchangeServiceInterface $currencyExchangeService
     */
    public function __construct(CurrencyExchangeServiceInterface $currencyExchangeService)
    {
        $this->currencyExchangeService = $currencyExchangeService;
    }

    /**
     * @return CommissionRulesFacadeInterface
     */
    public function makeCommissionRulesFacade(): CommissionRulesFacadeInterface
    {
        return new CommissionRulesFacade([
            TransactionTypeEnum::DEPOSIT()->getId() => new CommissionRulesChain(
                new DefaultDepositRule($this->currencyExchangeService)
            ),
            TransactionTypeEnum::WITHDRAW()->getId() => new CommissionRulesChain(
                (new PrivateAccountWithdrawRule($this->currencyExchangeService))
                    ->setNextRule(new BusinessAccountWithdrawRule($this->currencyExchangeService))
            )
        ]);
    }
}
