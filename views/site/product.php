<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Product $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
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
    ]) ?>

</div>