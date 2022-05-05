<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use App\AppKernel;
use App\DTO\TransactionReaderRequestDTO;
use App\Service\CommissionCalculationService;
use App\Service\CommissionCalculationServiceInterface;
use App\Service\TransactionReader\TransactionFileReader;
use App\Service\TransactionReader\TransactionReaderInterface;
use App\VO\Money;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Dotenv\Dotenv;

$container = initializeContainer();

try {
    /** @var TransactionReaderInterface $transactionFileReader */
    $transactionFileReader = $container->get(TransactionFileReader::class);
    /** @var CommissionCalculationServiceInterface $commissionCalculationService */
    $commissionCalculationService = $container->get(CommissionCalculationService::class);

    foreach ($transactionFileReader->readTransactions(new TransactionReaderRequestDTO($argv[1])) as $userHistoryUpToCurrentTransaction) {
        $transactionCommission = $commissionCalculationService->calculateCommission($userHistoryUpToCurrentTransaction);

        echo(getCommissionOutputFormat($transactionCommission));
    }
} catch (\Throwable $exception) {
    echo($exception->getMessage() . PHP_EOL);
}

function initializeContainer(): ContainerInterface
{
    error_reporting(0);
    (new Dotenv())->bootEnv('.env', '.env.dist');
    $kernel = new AppKernel($_ENV['ENV'], (bool)$_ENV['DEBUG']);
    $kernel->boot();
    return $kernel->getContainer();
}

function getCommissionOutputFormat(Money $commission): string
{
    return number_format($commission->getValue(), $commission->getCurrency()->getPrecision(), thousands_separator: '') . PHP_EOL;
}
