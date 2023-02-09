<?php

use yii\db\Migration;

/**
 * Class m230208_162549_insert_category_table
 */
class m230208_162549_insert_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function Up()
    {
        $this->batchInsert('category', ['title'], [
            ['Genshin Impact'],
            ['Fortnite'],
            ['League of Legends'],
            ['Dota 2'],
            ['CS GO'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function Down()
    {
        echo "m230208_162549_insert_category_table cannot be reverted.\n";

        return false;
    }
}