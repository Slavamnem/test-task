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

class CommissionRulesFacadeFactory
{
    private CommissionRulesFacade $commissionRulesFacade;

    public function __construct(
        private DefaultDepositRule $defaultDepositRule,
        private PrivateAccountWithdrawRule $privateAccountWithdrawRule,
        private BusinessAccountWithdrawRule $businessAccountWithdrawRule,
    ) {
    }

    public function makeCommissionRulesFacade(): CommissionRulesFacadeInterface
    {
        if (empty($this->commissionRulesFacade)) {
            $this->commissionRulesFacade = new CommissionRulesFacade([
                TransactionTypeEnum::Deposit->value => new CommissionRulesChain(
                    $this->defaultDepositRule
                ),
                TransactionTypeEnum::Withdraw->value => new CommissionRulesChain(
                    $this->privateAccountWithdrawRule
                        ->setNextRule($this->businessAccountWithdrawRule)
                ),
            ]);
        }

        return $this->commissionRulesFacade;
    }
}
