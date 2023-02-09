<?php

use yii\db\Migration;

/**
 * Class m230209_184814_insert_product_table
 */
class m230209_184814_insert_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('product', ['title', 'description', 'image', 'price'], [
            ['Кристаллов Сотворения x60', 'Бонус x60', 'uploads/60-genshin.png', 99],
            ['Кристаллов Сотворения x300', 'Бонус x300', 'uploads/300-genshin.png', 449],
            ['Кристаллов Сотворения x980', 'Бонус x980', 'uploads/980-genshin.png', 1390],
            ['Кристаллов Сотворения x1980', 'Бонус x1980', 'uploads/1980-genshin.png', 2790],
            ['Кристаллов Сотворения x3280', 'Бонус x3280', 'uploads/3280-genshin.png', 4690],
            ['Кристаллов Сотворения x6480', 'Бонус x6480', 'uploads/6480-genshin.png', 9490],
            ['Благословение Полой Луны', 'Бонус x300', 'uploads/moon-genshin.png', 449],
        ]);

        $this->batchInsert('product_category', ['product_id', 'category_id'], [
            [1, 1],
            [2, 1],
            [3, 1],
            [4, 1],
            [5, 1],
            [6, 1],
            [7, 1],
        ]);

        $this->batchInsert('product', ['title', 'description', 'image', 'price'], [
            ['Dream Flower Skin', '', 'uploads/fortnite-dreamflower-skin.png', 1500],
            ['Far Out Man Skin', '', 'uploads/fortnite-far-out-man-skin.png', 1500],
            ['Flower Power Bundle', '', 'uploads/fortnite-flower-power-bundle.png', 2300],
            ['Drumpbeat Pickaxe', '', 'uploads/fortnite-drumpbeat-pickaxe.png', 500],
        ]);

        $this->batchInsert('product_category', ['product_id', 'category_id'], [
            [8, 2],
            [9, 2],
            [10, 2],
            [11, 2],
        ]);

        $this->batchInsert('product', ['title', 'description', 'image', 'price'], [
            ['Прядильщица Историй Ирелия', '', 'uploads/lol-Irelia.jpg', 1820],
            ['Прядильщица Историй Сивир', '', 'uploads/lol-Sivir.jpg', 1350],
            ['Прядильщица Историй Зайра', '', 'uploads/lol-Zyra.jpg', 1350],
        ]);

        $this->batchInsert('product_category', ['product_id', 'category_id'], [
            [12, 3],
            [13, 3],
            [14, 3],
        ]);

        $this->batchInsert('product', ['title', 'description', 'image', 'price'], [
            ['The Abscesserator', 'Почему это сочные кишочки должны украшать лишь подбородок?', 'uploads/dota2-the-abscesserator.png', 309],
            ['Mace of Aeons', 'Удар, несущий мощь тысячи взмахов в мгновение ока, растянутое на необъятности времени. Удар, несущий смерть и поглощающий вечность.', 'uploads/dota2-mace-of-aeons.png', 17241],
            ['Inscribed Righteous Thunderbolt', 'Паря меж облаков, прыгая от грозы к грозе, праведный гром бороздит небеса, готовый поразить всякого, на кого укажет Zeus.', 'uploads/dota2-inscribed-righteous-thunderbolt.png', 1213],
        ]);

        $this->batchInsert('product_category', ['product_id', 'category_id'], [
            [15, 4],
            [16, 4],
            [17, 4],
        ]);

        $this->batchInsert('product', ['title', 'description', 'image', 'price'], [
            ['AWP | Mortis (Field-Tested)', '', 'uploads/cs-go-awp-mortis.png', 124],
            ['USP-S | Cortex (Field-Tested)', '', 'uploads/cs-go-usp-p-cortex.png', 146],
        ]);

        $this->batchInsert('product_category', ['product_id', 'category_id'], [
            [18, 5],
            [19, 5],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230209_184814_insert_product_table cannot be reverted.\n";

        return false;
    }
}
