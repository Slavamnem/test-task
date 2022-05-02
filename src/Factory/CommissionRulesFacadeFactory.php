<?php

declare(strict_types=1);

namespace App\Factory;

use App\Enum\TransactionTypeEnum;
use App\Service\CommissionRulesChain\CommissionRulesChain;
use App\Service\CommissionRulesChain\CommissionRulesFacade;
use App\Service\CommissionRulesChain\CommissionRulesFacadeInterface;
use App\Service\CommissionRulesChain\DepositChain\DefaultDepositRule;
use App\Service\CommissionRulesChain\WithdrawChain\BusinessAccountWithdrawRule;
use App\Service\CommissionRulesChain\WithdrawChain\PrivateAccountWithdrawRule;
use App\Service\CurrencyExchangeServiceInterface;
use App\Service\MoneyCalculator\MoneyCalculatorInterface;

class CommissionRulesFacadeFactory
{
    private static CommissionRulesFacade $commissionRulesFacade;

    public function __construct(private MoneyCalculatorInterface $moneyCalculator, private CurrencyExchangeServiceInterface $currencyExchangeService) {}

    public function makeCommissionRulesFacade(): CommissionRulesFacadeInterface
    {
        if (empty(self::$commissionRulesFacade)) {
            self::$commissionRulesFacade = new CommissionRulesFacade([
                TransactionTypeEnum::Deposit->value => new CommissionRulesChain(
                    new DefaultDepositRule($this->moneyCalculator)
                ),
                TransactionTypeEnum::Withdraw->value => new CommissionRulesChain(
                    (new PrivateAccountWithdrawRule($this->moneyCalculator, $this->currencyExchangeService))
                        ->setNextRule(new BusinessAccountWithdrawRule($this->moneyCalculator))
                )
            ]);
        }

        return self::$commissionRulesFacade;
    }
}
