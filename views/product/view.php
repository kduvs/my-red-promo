<?php

use yii\widgets\DetailView;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

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
        'template' => "<tr><th style='width: 15%;'>{label}</th><td>{value}</td></tr>",
        // 'options' => ['style' => 'width: auto;'],
        'options' => ['class' => 'table table-striped table-bordered detail-view', 'style' => 'width: 40%;'],
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

    <?php
    if (!is_null($review)) {
        $form = ActiveForm::begin();
        echo $form->field($review, 'text')->textarea(['rows' => 3]);
        echo Html::submitButton('Опубликовать', ['class' => 'btn btn-primary']);
        ActiveForm::end();
    }
    ?>
</div>
