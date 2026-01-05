<?php

namespace common\modules\loan\repositories;

use common\modules\loan\models\Loan;
use yii\db\Exception;

/**
 * Репозиторий для работы с заявками
 */
interface LoanRepositoryInterface
{
    public function getAllByUserId(int $userId): array;

    /**
     * Есть ли одобренные заявки
     */
    public function hasApprovedLoan(int $userId): bool;
    /**
     * @throws Exception
     */
    public function save(Loan $loan): void;
}