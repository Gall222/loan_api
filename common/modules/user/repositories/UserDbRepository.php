<?php

namespace common\modules\user\repositories;

use common\modules\user\models\User;

/**
 * {@inheritdoc}
 */
class UserDbRepository implements UserRepositoryInterface
{
    public function getAll(): array
    {
        return User::find()->all();
    }
}