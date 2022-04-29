<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use App\AppKernel;
use App\Service\TransactionReader\TransactionFileReader;
use App\Service\TransactionReader\TransactionReaderInterface;
use App\DTO\TransactionReaderRequest\TransactionFileReaderRequestDTO;
use App\Service\CommissionCalculationServiceInterface;
use App\Service\CommissionCalculationService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Dotenv\Dotenv;

const COMMISSION_PRECISION = 2;

$container = createContainer();

try {
    /** @var TransactionReaderInterface $transactionFileReader */
    $transactionFileReader = $container->get(TransactionFileReader::class);
    /** @var CommissionCalculationServiceInterface $commissionCalculationService */
    $commissionCalculationService = $container->get(CommissionCalculationService::class);

    foreach ($transactionFileReader->readTransactions(new TransactionFileReaderRequestDTO($argv[1])) as $userHistoryUpToCurrentTransaction) {
        $transactionCommission = $commissionCalculationService->calculateCommission($userHistoryUpToCurrentTransaction);

        echo(getCommissionOutputFormat($transactionCommission));
    }
} catch (\Throwable $exception) {
    echo($exception->getMessage() . PHP_EOL);
}

function createContainer(): ContainerInterface
{
    (new Dotenv())->bootEnv('.env');
    $kernel = new AppKernel($_ENV['ENV'], (bool)$_ENV['DEBUG']);
    $kernel->boot();
    return $kernel->getContainer();
}

function getCommissionOutputFormat(float $commission): string
{
    return number_format($commission, COMMISSION_PRECISION, thousands_separator: '') . PHP_EOL;
}
