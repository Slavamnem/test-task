<?php declare(strict_types=1);

namespace App\Tests\Functional;

use App\DTO\ExchangeRatesResponseDTO;
use App\Enum\CurrencyEnum;
use App\Http\ExchangeRatesHttpInterface;
use PHPUnit\Framework\TestCase;

class ScriptTest extends TestCase
{
    public function test_script(): void
    {
        /*$exchangeRatesMock = $this->getMockBuilder(ExchangeRatesHttpInterface::class)->getMock();

        $exchangeRatesMock->method('getCurrentExchangeRates')->willReturn(
            new ExchangeRatesResponseDTO(CurrencyEnum::Eur->value, (new \DateTime())->format('Y-m-d'), [
                CurrencyEnum::Eur->value => 1,
                CurrencyEnum::Usd->value => 1.1497,
                CurrencyEnum::Jpy->value => 129.53,
            ])
        );

        $res = $exchangeRatesMock->getCurrentExchangeRates();

        $this->assertEquals(1, $res->getRate(CurrencyEnum::Eur->value));*/
    }
}
