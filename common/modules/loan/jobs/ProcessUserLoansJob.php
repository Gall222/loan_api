<?php

declare(strict_types=1);

namespace common\modules\loan\jobs;

use common\modules\loan\services\LoanServiceInterface;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

/**
 * Смена статусов заявок для пользователя
 */
class ProcessUserLoansJob extends BaseObject implements JobInterface
{
    public int $userId;
    public int $delay;

    public function execute($queue)
    {
        $service = Yii::$container->get(LoanServiceInterface::class);
        $service->changeStatusesProcess($this->userId, $this->delay);
    }
}
