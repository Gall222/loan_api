<?php

namespace common\modules\loan\dto;

/**
 * Запрос на создание заявки
 */
class LoanCreateRequestDto
{
    private int $user_id;
    private int $amount;
    private int $term;

    public function __construct(int $user_id, int $amount, int $term)
    {
        $this->user_id = $user_id;
        $this->amount = $amount;
        $this->term = $term;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getTerm(): int
    {
        return $this->term;
    }
}