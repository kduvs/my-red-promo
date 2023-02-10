<?php

use yii\db\Migration;
use yii\db\Expression;

/**
 * Class m230209_220212_insert_review_table
 */
class m230209_220212_insert_review_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Якобы правдоподбное заполнение (хотя можно было объеденить все в один метод с рандомом, а не сорить тут)
        $this->batchInsert('review', ['user_id', 'product_id', 'created_at', 'text'], $this->generateReviews(1, 10));
        $this->batchInsert('review', ['user_id', 'product_id', 'created_at', 'text'], $this->generateReviews(2, 5));
        $this->batchInsert('review', ['user_id', 'product_id', 'created_at', 'text'], $this->generateReviews(3, 3));
        $this->batchInsert('review', ['user_id', 'product_id', 'created_at', 'text'], $this->generateReviews(4, 2));
        $this->batchInsert('review', ['user_id', 'product_id', 'created_at', 'text'], $this->generateReviews(5, 6));
        $this->batchInsert('review', ['user_id', 'product_id', 'created_at', 'text'], $this->generateReviews(6, 15));
        $this->batchInsert('review', ['user_id', 'product_id', 'created_at', 'text'], $this->generateReviews(7, 30));

        $this->batchInsert('review', ['user_id', 'product_id', 'created_at', 'text'], $this->generateReviews(8, 4));
        $this->batchInsert('review', ['user_id', 'product_id', 'created_at', 'text'], $this->generateReviews(9, 3));
        $this->batchInsert('review', ['user_id', 'product_id', 'created_at', 'text'], $this->generateReviews(10, 12));
        $this->batchInsert('review', ['user_id', 'product_id', 'created_at', 'text'], $this->generateReviews(11, 1));

        $this->batchInsert('review', ['user_id', 'product_id', 'created_at', 'text'], $this->generateReviews(12, 30));
        $this->batchInsert('review', ['user_id', 'product_id', 'created_at', 'text'], $this->generateReviews(13, 8));
        $this->batchInsert('review', ['user_id', 'product_id', 'created_at', 'text'], $this->generateReviews(14, 5));

        $this->batchInsert('review', ['user_id', 'product_id', 'created_at', 'text'], $this->generateReviews(15, 2));
        $this->batchInsert('review', ['user_id', 'product_id', 'created_at', 'text'], $this->generateReviews(16, 1));
        $this->batchInsert('review', ['user_id', 'product_id', 'created_at', 'text'], $this->generateReviews(17, 3));

        $this->batchInsert('review', ['user_id', 'product_id', 'created_at', 'text'], $this->generateReviews(18, 7));
        $this->batchInsert('review', ['user_id', 'product_id', 'created_at', 'text'], $this->generateReviews(19, 1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('review', ['user_id' => 2]);
    }

    private function generateReviews($product_id, $count) {
        $result = [];
        for ($i = 1; $i <= $count; $i++) {
            array_push($result, [2, $product_id, new Expression('NOW()'), 'Текст отзыва ' . $i]);
        }
        return $result;
    }
}
