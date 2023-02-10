<?php

use yii\widgets\DetailView;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\Product $model */

$this->title = $model->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">
    <?php Pjax::begin(); ?>
    <div class="d-flex justify-content-center gap-5 mt-5">
        <div class="list-group ml-auto w-auto">
            <?php 
            echo DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'title',
                    'description:ntext',
                    'price',
                    [
                        'label' => 'Изображение',
                        'format' => 'raw',
                        'value' => function($data) {
                            return Html::img(\Yii::$app->request->BaseUrl . '/' . $data->image ,[
                                'style' => 'height: 200px'
                            ]);
                        },
                    ],
                ],
            ]);
            
            if(\Yii::$app->user->identity->isAdmin) {
                $this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
                echo Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary w-25']);
                echo Html::a('Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger w-25 mt-2',
                    'data' => [
                        'confirm' => 'Вы точно хотите удалть этот товар?',
                        'method' => 'post',
                    ],
                ]);
            }
            $this->params['breadcrumbs'][] = $this->title;
            ?>
        </div>
        <div class="list-group mx-auto w-auto">
            <div class="list-group w-auto">
            <h2>Отзывы</h2>
            <?php foreach ($reviews as $item): ?>
                <div class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
                    <div class="d-flex gap-5 w-100 justify-content-between">
                    <div>
                        <h6 class="mb-0"><?= $item->user->name;?></h6>
                        <p class="mb-0 opacity-75"><?= $item->text;?></p>
                    </div>
                    <small class="opacity-50 text-nowrap"><?= $item->created_at; ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
            <?= LinkPager::widget(['pagination' => $pagination]) ?>
            </div>
        </div>
    </div>

    

    <?php
    if (!is_null($review)) {
        $form = ActiveForm::begin();
        echo $form->field($review, 'text')->textarea(['rows' => 3]);
        echo Html::submitButton('Опубликовать', ['class' => 'btn btn-primary']);
        ActiveForm::end();
    }
    Pjax::end();
    ?>
</div>
