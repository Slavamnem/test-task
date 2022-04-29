<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use PHPUnit\Framework\TestCase;

class ScriptTest extends TestCase
{
    public function test_script(): void
    {
        $commandOutput = `php script.php test.csv test`;
        $commissions = explode("\n", trim($commandOutput));

        foreach ($this->getExpectedCommissions() as $key => $expectedCommission) {
            $this->assertEquals($expectedCommission, $commissions[$key]);
        }
    }

    private function getExpectedCommissions(): array
    {
        return [
            '0.60',
            '3.00',
            '0.00',
            '0.06',
            '1.50',
            '0.00',
            '0.69',
            '0.30',
            '0.30',
            '3.00',
            '0.00',
            '0.00',
            '8611.41',
        ];
    }
}
