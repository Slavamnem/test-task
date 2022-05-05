<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Collection\TransactionsCollection;
use App\DTO\ExchangeRatesResponseDTO;
use App\DTO\SourceFileLineDTO;
use App\Enum\CurrencyEnum;
use App\Factory\CommissionRulesFacadeFactory;
use App\Factory\TransactionDTOFactory;
use App\Http\ExchangeRatesHttpInterface;
use App\Service\CommissionCalculationService;
use App\Service\CommissionRulesChain\DepositChain\DefaultDepositRule;
use App\Service\CommissionRulesChain\WithdrawChain\BusinessAccountWithdrawRule;
use App\Service\CommissionRulesChain\WithdrawChain\PrivateAccountWithdrawRule;
use App\Service\CurrencyExchangeService;
use App\Service\MoneyCalculator\MoneyBCMathCalculator;
use App\Service\MoneyCalculator\MoneyCalculatorInterface;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class CommissionCalculationServiceTest extends TestCase
{
    private const EUR_TO_EUR_EXCHANGE_RATE = 1;
    private const EUR_TO_USD_EXCHANGE_RATE = 1.1497;
    private const EUR_TO_JPY_EXCHANGE_RATE = 129.53;

    private const MONEY_PRECISION = 4;
    private const DEFAULT_CURRENCY = 'EUR';

    private const WITHDRAW_PRIVATE_ACCOUNT_FREE_TRANSACTIONS_PER_WEEK = 3;
    private const WITHDRAW_PRIVATE_ACCOUNT_COMMISSION_PERCENT = 0.3;
    private const WITHDRAW_PRIVATE_ACCOUNT_FREE_SUM = 1000;
    private const WITHDRAW_BUSINESS_ACCOUNT_COMMISSION_PERCENT = 0.5;
    private const DEPOSIT_COMMISSION_PERCENT = 0.03;

    public function test_commission_calculation(): void
    {
        $moneyCalculator = new MoneyBCMathCalculator(self::MONEY_PRECISION);

        $commissionRulesFacadeFactory = new CommissionRulesFacadeFactory(
            new DefaultDepositRule($moneyCalculator, self::DEPOSIT_COMMISSION_PERCENT),
            $this->getPrivateAccountWithdrawRule($moneyCalculator),
            new BusinessAccountWithdrawRule($moneyCalculator, self::WITHDRAW_BUSINESS_ACCOUNT_COMMISSION_PERCENT),
        );

        $commissionCalculationService = new CommissionCalculationService($commissionRulesFacadeFactory);

        foreach ($this->getTransactionsExpectedCommissions() as $transactionId => $expectedCommission) {
            $this->assertEquals(
                $expectedCommission,
                $commissionCalculationService
                    ->calculateCommission($this->getUserHistoryUpToCurrentTransaction($transactionId))
                    ->getValue()
            );
        }
    }

    private function getExchangeRatesHttpMock(): ExchangeRatesHttpInterface
    {
        $exchangeRatesHttpMock = $this
            ->getMockBuilder(ExchangeRatesHttpInterface::class)
            ->getMock()
        ;

        $exchangeRatesHttpMock
            ->method('getCurrentExchangeRates')
            ->willReturn(
                new ExchangeRatesResponseDTO(
                    CurrencyEnum::Eur->value,
                    (new DateTimeImmutable())->format('Y-m-d'),
                    [
                        CurrencyEnum::Eur->value => self::EUR_TO_EUR_EXCHANGE_RATE,
                        CurrencyEnum::Usd->value => self::EUR_TO_USD_EXCHANGE_RATE,
                        CurrencyEnum::Jpy->value => self::EUR_TO_JPY_EXCHANGE_RATE,
                    ]
                )
            )
        ;

        return $exchangeRatesHttpMock;
    }

    private function getPrivateAccountWithdrawRule(MoneyCalculatorInterface $moneyCalculator): PrivateAccountWithdrawRule
    {
        return new PrivateAccountWithdrawRule(
            $moneyCalculator,
            new CurrencyExchangeService(
                $this->getExchangeRatesHttpMock(),
                $moneyCalculator,
                self::DEFAULT_CURRENCY
            ),
            self::DEFAULT_CURRENCY,
            self::WITHDRAW_PRIVATE_ACCOUNT_FREE_TRANSACTIONS_PER_WEEK,
            self::WITHDRAW_PRIVATE_ACCOUNT_COMMISSION_PERCENT,
            self::WITHDRAW_PRIVATE_ACCOUNT_FREE_SUM,
        );
    }

    private function getUserHistoryUpToCurrentTransaction(int $currentTransactionNum): TransactionsCollection
    {
        $userHistoryUpToCurrentTransaction = new TransactionsCollection();
        $transactionDtoFactory = new TransactionDTOFactory();
        $transactionsData = $this->getTransactionsData();

        foreach (array_slice($transactionsData, 0, $currentTransactionNum + 1) as $transactionData) {
            //1 - userId; collect current transaction's user history, ignoring other transactions
            if ($transactionData[1] !== $transactionsData[$currentTransactionNum][1]) {
                continue;
            }

            $userHistoryUpToCurrentTransaction->addTransaction(
                $transactionDtoFactory->createTransactionDTOFromSourceFileLineDTO(new SourceFileLineDTO(
                    $transactionData[0],
                    $transactionData[1],
                    $transactionData[2],
                    $transactionData[3],
                    $transactionData[4],
                    $transactionData[5]
                ))
            );
        }

        return $userHistoryUpToCurrentTransaction;
    }

    private function getTransactionsData(): array
    {
        return [
            ['2014-12-31', 4, 'private', 'withdraw', 1200.00, 'EUR'],
            ['2015-01-01', 4, 'private', 'withdraw', 1000.00, 'EUR'],
            ['2016-01-05', 4, 'private', 'withdraw', 1000.00, 'EUR'],
            ['2016-01-05', 1, 'private', 'deposit', 200.00, 'EUR'],
            ['2016-01-06', 2, 'business', 'withdraw', 300.00, 'EUR'],
            ['2016-01-06', 1, 'private', 'withdraw', 30000, 'JPY'],
            ['2016-01-07', 1, 'private', 'withdraw', 1000.00, 'EUR'],
            ['2016-01-07', 1, 'private', 'withdraw', 100.00, 'USD'],
            ['2016-01-10', 1, 'private', 'withdraw', 100.00, 'EUR'],
            ['2016-01-10', 2, 'business', 'deposit', 10000.00, 'EUR'],
            ['2016-01-10', 3, 'private', 'withdraw', 1000.00, 'EUR'],
            ['2016-02-15', 1, 'private', 'withdraw', 300.00, 'EUR'],
            ['2016-02-19', 5, 'private', 'withdraw', 3000000, 'JPY'],
        ];
    }

    private function getTransactionsExpectedCommissions(): array
    {
        return [
            0.60,
            3.00,
            0.00,
            0.06,
            1.50,
            0,
            0.70,
            0.30,
            0.30,
            3.00,
            0.00,
            0.00,
            8612,
        ];
    }
}
