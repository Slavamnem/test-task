<?php declare(strict_types=1);

require 'vendor/autoload.php';

use App\Service\CommissionCalculationService;
use App\DTO\SourceFileLineDTO;
use App\Helper\ValidationHelper;
use App\AppKernel;
use App\Service\TransactionsFileReader;
use App\Service\TransactionsFileReaderInterface;
use App\Factory\TransactionDTOFactory;
use App\Collection\TransactionsCollection;
use App\DTO\TransactionDTO;
use Symfony\Component\Console\Application;


$kernel = new AppKernel('dev', true);
$kernel->boot();
$container = $kernel->getContainer();
//$application = $container->get(Application::class); //$application->run();


try {
    $sourceFileName = $argv[1];

    $sourceFile = fopen($sourceFileName, 'r');

    $currentFileLine = 1;

    while (($sourceFileLine = fgetcsv($sourceFile)) !== FALSE) {
        $sourceFileLineDTO = new SourceFileLineDTO($sourceFileLine[0], (int)$sourceFileLine[1], $sourceFileLine[2], $sourceFileLine[3], (float)$sourceFileLine[4], $sourceFileLine[5]);
        ValidationHelper::validateAndThrowException($sourceFileLineDTO);


        //Для подчсчета комиссии надо помнить историю операций пользователя. И чтобы не хранить всю историю, подгружаю на каждом шаге только список транзакций текущего пользователя.
        /** @var TransactionsFileReaderInterface $transactionsFileReader */
        $transactionsFileReader = $container->get(TransactionsFileReader::class);
        $userTransactionsCollection = $transactionsFileReader->getAllUserTransactionsUpToCurrent($sourceFileName, $sourceFileLineDTO->getUserId(), $currentFileLine);


        /** @var CommissionCalculationService $commissionCalculationService */
        $commissionCalculationService = $container->get(CommissionCalculationService::class);
        $commission = $commissionCalculationService->calculateFee($userTransactionsCollection);


        echo $commission . PHP_EOL;
        $currentFileLine++;
//        dump($userTransactionsCollection->getSize());
    }

    fclose($sourceFile);
} catch (\Throwable $exception) {
    echo $exception->getMessage() . PHP_EOL;
}
