<?php

use common\modules\loan\mappers\LoanMapper;
use common\modules\loan\mappers\LoanMapperInterface;
use common\modules\loan\repositories\LoanDbRepository;
use common\modules\loan\repositories\LoanRepositoryInterface;
use common\modules\loan\services\LoanService;
use common\modules\loan\services\LoanServiceInterface;
use common\modules\user\repositories\UserDbRepository;
use common\modules\user\repositories\UserRepositoryInterface;

Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');


Yii::$container->set(LoanServiceInterface::class, LoanService::class);
Yii::$container->set(LoanRepositoryInterface::class, LoanDbRepository::class);
Yii::$container->set(UserRepositoryInterface::class, UserDbRepository::class);
Yii::$container->set(LoanMapperInterface::class, LoanMapper::class);