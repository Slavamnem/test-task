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
    public function __construct(private CurrencyExchangeServiceInterface $currencyExchangeService) {}

    public function makeCommissionRulesFacade(): CommissionRulesFacadeInterface
    {
        return new CommissionRulesFacade([
            TransactionTypeEnum::Deposit->value => new CommissionRulesChain(
                new DefaultDepositRule($this->currencyExchangeService)
            ),
            TransactionTypeEnum::Withdraw->value => new CommissionRulesChain(
                (new PrivateAccountWithdrawRule($this->currencyExchangeService))
                    ->setNextRule(new BusinessAccountWithdrawRule($this->currencyExchangeService))
            )
        ]);
    }
}
