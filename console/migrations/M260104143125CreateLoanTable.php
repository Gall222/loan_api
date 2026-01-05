<?php

use \yii\db\Migration;

/**
 * Создание таблицы для заявок
 * Добавляется уникальный частичный индекс для статусов approved
 */
class M260104143125CreateLoanTable extends Migration
{
    private const TABLE_NAME = '{{%loans}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->comment('Id клиента'),
            'amount' => $this->integer()->notNull()->comment('Сумма займа'),
            'term' => $this->integer()->notNull()->comment('Срок займа в днях'),
            'status' => $this
                ->string()
                ->null()
                ->defaultValue('declined')
                ->comment('Статус заявки'),
            'created_at' => $this
                ->dateTime()
                ->notNull()
                ->defaultValue(date('Y-m-d H:i:s'))
                ->comment('Дата создания записи'),
        ]);

        $this->addCommentOnTable(self::TABLE_NAME,'Таблица займов');

        $this->addForeignKey(
            'fk-loan-user_id',
            self::TABLE_NAME,
            'user_id',
            '{{%users}}',
            'id'
        );

        $this->execute("
        CREATE UNIQUE INDEX uniq_approved_loan_per_user
        ON loans (user_id)
        WHERE status = 'approved'
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function down(): void
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
