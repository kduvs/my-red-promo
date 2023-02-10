<?php

/** @var yii\web\View $this */

use yii\widgets\LinkPager;

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <ul class="list-group list-group-horizontal pr-auto" style="gap: 30px;">
        <li class="list-group-item" style="width: 30%;">
            <h3>Избранные товары</h3>
            <div class="list-group">
                <?php
                if (!isset($products)) {
                    foreach ($products as $product) {
                        echo $this->render('_product', ['product' => $product]);
                    } 
                    echo LinkPager::widget(['pagination' => $product_pagination]);
                } else {
                    echo 'Вы еще не добавляли ни один товар в Избранное';
                }
                ?>
            </div>
        </li>
        <li class="list-group-item">
            <h3>Популярные категории</h3>
            <div class="list-group list-group-horizontal pr-auto" style="height: 75px;">
                <?php foreach ($categories as $category) {
                    echo $this->render('_category', ['category' => $category]);
                } ?>
            </div>
        </li>
    </ul>
</div>
