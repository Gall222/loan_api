<?php

namespace common\modules\user\repositories;


/**
 * Репозиторий для работы с данными клиентов
 */
interface UserRepositoryInterface
{
    public function getAll(): array;
}