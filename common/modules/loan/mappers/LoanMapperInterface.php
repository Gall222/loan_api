<?php

namespace common\modules\loan\mappers;

use common\modules\loan\dto\LoanCreateRequestDto;

/**
 * Класс для обработки и наполнения модели заявки
 */
interface LoanMapperInterface
{
    /**
     * Обрабатывает данные из запроса
     */
    public function getCorrectRequest(array $request): LoanCreateRequestDto;
}