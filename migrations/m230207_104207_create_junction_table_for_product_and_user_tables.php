<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product_user}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%product}}`
 * - `{{%user}}`
 */
class m230207_104207_create_junction_table_for_product_and_user_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product_user}}', [
            'product_id' => $this->integer(),
            'user_id' => $this->integer(),
            'PRIMARY KEY(product_id, user_id)',
        ]);

        // creates index for column `product_id`
        $this->createIndex(
            '{{%idx-product_user-product_id}}',
            '{{%product_user}}',
            'product_id'
        );

        // add foreign key for table `{{%product}}`
        $this->addForeignKey(
            '{{%fk-product_user-product_id}}',
            '{{%product_user}}',
            'product_id',
            '{{%product}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-product_user-user_id}}',
            '{{%product_user}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-product_user-user_id}}',
            '{{%product_user}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%product}}`
        $this->dropForeignKey(
            '{{%fk-product_user-product_id}}',
            '{{%product_user}}'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            '{{%idx-product_user-product_id}}',
            '{{%product_user}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-product_user-user_id}}',
            '{{%product_user}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-product_user-user_id}}',
            '{{%product_user}}'
        );

        $this->dropTable('{{%product_user}}');
    }
}
