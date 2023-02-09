<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Product $model */

$this->title = $model->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if(\Yii::$app->user->identity->isAdmin) {
            $this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
            echo Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
            echo Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы точно хотите удалть этот товар?',
                    'method' => 'post',
                ],
            ]);
        }
        $this->params['breadcrumbs'][] = $this->title;
        ?>
    </p>

    <?= DetailView::widget([
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
    ]) ?>

</div>
