<?php

use yii\db\Migration;

/**
 * Class m230210_064940_insert_product_user_table
 */
class m230210_064940_insert_product_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('product_user', ['product_id', 'user_id'], [
            [7, 2],
            [12, 2],
            [6, 2],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('product_user', ['user_id' => 2]);
    }
}
