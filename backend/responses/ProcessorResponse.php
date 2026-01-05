<?php

namespace backend\responses;

/**
 * Ответ обновления статусов заявок
 */
class ProcessorResponse
{
    public bool $result;

    public function __construct(bool $result)
    {
        $this->result = $result;
    }
}