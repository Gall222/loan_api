<?php

declare(strict_types=1);

namespace console\controllers;;

use common\modules\loan\services\LoanServiceInterface;
use Yii;
use yii\console\Controller;

/**
 * Имитация многократного обращения к апи
 * В контейнере(make shell-php) вызвать php yii test/test
 */
final class TestController extends Controller
{
    public function actionTest()
    {
        $service = Yii::$container->get(LoanServiceInterface::class);

        $numRequests = 100;

        echo "=== Simulating $numRequests create requests ===\n";

        for ($i = 0; $i < $numRequests; $i++) {
            $pid = pcntl_fork();
            if ($pid == -1) {
                die("Could not fork process $i\n");
            } elseif ($pid == 0) {
                // Дочерний процесс
                try {
                    $response = $service->create([
                        'user_id' => 1,
                        'amount' => rand(100, 1000),
                        'term' => rand(1, 12)
                    ]);
                    echo "Child $i: create result = " . ($response->result ? "success" : "failed") . "\n";
                } catch (\Throwable $e) {
                    echo "Child $i: exception = " . $e->getMessage() . "\n";
                }
                exit(0);
            }
        }

        // Ждем завершения всех дочерних процессов
        while (pcntl_waitpid(0, $status) != -1);

        echo "=== Simulating $numRequests change statuses ===\n";

        for ($i = 0; $i < $numRequests; $i++) {
            $pid = pcntl_fork();
            if ($pid == -1) {
                die("Could not fork process $i\n");
            } elseif ($pid == 0) {
                try {
                    $response = $service->addChangeStatusesJob(0); // без задержки для теста
                    echo "Child $i: change statuses result = " . ($response->result ? "success" : "failed") . "\n";
                } catch (\Throwable $e) {
                    echo "Child $i: exception = " . $e->getMessage() . "\n";
                }
                exit(0);
            }
        }

        while (pcntl_waitpid(0, $status) != -1);

        echo "=== Concurrency test finished ===\n";
    }
}
