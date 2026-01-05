<?php

use yii\db\Migration;

class M260104142631CreateUserTable extends Migration
{
    private const TABLE_NAME = '{{%users}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->comment('Имя клиента'),
            'created_at' => $this
                ->dateTime()
                ->notNull()
                ->defaultValue(date('Y-m-d H:i:s'))
                ->comment('Дата создания записи'),
        ]);

        $this->addCommentOnTable(self::TABLE_NAME,'Таблица клиентов');
    }

    /**
     * {@inheritdoc}
     */
    public function down(): void
    {
        $this->dropTable(self::TABLE_NAME);
    }
}