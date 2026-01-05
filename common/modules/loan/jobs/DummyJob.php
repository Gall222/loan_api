<?php

declare(strict_types=1);

namespace common\modules\loan\jobs;

use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

/**
 * Задача для проверки очереди
 */
class DummyJob extends BaseObject implements JobInterface
{
    public const DUMMY_JOB_KEY = 'dummy_job_last_run';
    public function execute($queue)
    {
        if (Yii::$app->has('redis')) {
            Yii::$app->redis->set(self::DUMMY_JOB_KEY, time());
        }
    }
}
