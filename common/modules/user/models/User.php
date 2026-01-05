<?php

namespace common\modules\user\models;

use yii\db\ActiveRecord;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;
use yii\validators\StringValidator;

/**
 * Модель пользователя
 *
 * @property integer $id
 * @property string $name Имя клиента
 * @property string $created_at Дата записи
 */
class User extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%users}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], RequiredValidator::class],
            [['name'], StringValidator::class],
            [['created_at'], SafeValidator::class],
        ];
    }
}
