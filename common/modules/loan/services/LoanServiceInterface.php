<?php

namespace common\modules\loan\services;

use backend\responses\ProcessorResponse;
use backend\responses\LoanCreateResponse;

/**
 * Сервис обработки заявок
 */
interface LoanServiceInterface
{
    /**
     * Ставим в очередь смену статусов для пользователей
     * @param int $delay Задержка на принятие решения
     */
    public function addChangeStatusesJob(int $delay): ProcessorResponse;

    public function create(array $request): LoanCreateResponse;

    /**
     * Команда смены статусов для пользователя из очереди
     */
    public function changeStatusesProcess(int $userId, int $delay): void;
}