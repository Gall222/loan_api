<?php

use \yii\db\Migration;

class M260104143450SeedUserTable extends Migration
{
    private const TABLE_NAME = '{{%users}}';

    /**
     * {@inheritdoc}
     */
    public function up(): void
    {
        $this->insert(self::TABLE_NAME, [
            'name' => 'Goodman',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down(): void
    {
        $this->delete('{{%loans}}');
        $this->delete(self::TABLE_NAME);
    }
}
