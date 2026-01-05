<?php

namespace common\modules\loan\mappers;

use common\modules\loan\dto\LoanCreateRequestDto;

/**
 * {@inheritdoc}
 */
class LoanMapper implements LoanMapperInterface
{
    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function getCorrectRequest(array $request): LoanCreateRequestDto
    {
        try {
            return new LoanCreateRequestDto(
                $request['user_id'],
                $request['amount'],
                $request['term'],
            );
        } catch (\Exception $e) {
            throw new \Exception('Неверно переданы данные для создания заявки');
        }
    }
}