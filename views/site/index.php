<?php

/** @var yii\web\View $this */

use yii\widgets\LinkPager;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\widgets\ListView;

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <ul class="list-group list-group-horizontal pr-auto" style="gap: 30px;">
        <li class="list-group-item" style="width: 30%;">
            <h3>Избранные товары</h3>
            <div class="list-group">
                <?php
                if (count($products) !== 0) {
                    foreach ($products as $product) {
                        echo $this->render('_product', ['product' => $product]);
                    } 
                    echo LinkPager::widget(['pagination' => $productPagination]);
                } else {
                    if (!Yii::$app->user->isGuest) echo 'Вы еще не добавляли ни один товар в Избранное';
                    else echo 'Чтобы добавить товар в Избранное, вам необходимо авторизоваться';
                }
                ?>
            </div>
        </li>
        <li class="list-group-item">
            <h3>Популярные категории</h3>
            <div class="list-group list-group-horizontal pr-auto">
                <?php foreach ($categories as $category) {
                    echo $this->render('_category', ['category' => $category]);
                } ?>
            </div>
            <br>
            <?php $form = ActiveForm::begin([
                'method' => 'post',
            ]);

            echo $form->field($searchModel, 'input')->textInput()->label(false);

            echo Html::submitButton('Поиск', ['class' => 'btn btn-primary']);

            ActiveForm::end(); ?>
            <div class="list-group w-auto">
                <?php if (count($searchCategories) !== 0) {
                    echo Html::tag('h3', 'Результаты по Категориям');
                    foreach ($searchCategories as $searchCategory) {
                        echo $this->render('searchCategory', ['searchCategory' => $searchCategory]);
                    }
                    LinkPager::widget(['pagination' => $searchCategoryPagination]);
                } ?>
            <div class="list-group w-auto">

            <div class="list-group w-auto">
                <?php if (count($searchProducts) !== 0) {
                    echo Html::tag('h3', 'Результаты по Товарам');
                    foreach ($searchProducts as $searchProduct) {
                        echo $this->render('searchProduct', ['searchProduct' => $searchProduct]);
                    }
                    LinkPager::widget(['pagination' => $searchProductPagination]);
                } ?>
            <div class="list-group w-auto">
        </li>
    </ul>
</div>
