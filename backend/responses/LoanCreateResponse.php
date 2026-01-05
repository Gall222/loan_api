<?php

namespace backend\responses;

/**
 * Ответ сервиса создания заявок
 */
class LoanCreateResponse
{
    public bool $result;

    /**
     * Id созданной заявки
     */
    public ?int $id;

    public function __construct(bool $result, ?int $id = null)
    {
        $this->result = $result;
        $this->id = $id;
    }
}