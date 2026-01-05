<?php

namespace common\modules\loan\repositories;

use common\modules\loan\models\Loan;
use yii\db\Exception;

/**
 * {@inheritdoc}
 */
class LoanDbRepository implements LoanRepositoryInterface
{
    public function getAllByUserId(int $userId): array
    {
        return Loan::find()->where(['user_id' => $userId])->all();
    }

    /**
     * {@inheritdoc}
     */
    public function hasApprovedLoan(int $userId): bool
    {
        $count = Loan::find()
            ->where(['user_id' => $userId])
            ->andWhere(['status' => Loan::STATUS_APPROVED])
            ->limit(1)
            ->count();

        return $count > 0;
    }

    /**
     * @throws Exception
     */
    public function save(Loan $loan): void
    {
        if ($loan->save() === false) {
            throw new Exception('Save failed!');
        }
    }
}