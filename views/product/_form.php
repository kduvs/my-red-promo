<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Product $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'image')->fileInput() ?>

    <?php
        if ($model->image) {
            echo '<b>Прикреплённый файл:</b><br>';
            echo Html::img(
                \Yii::$app->request->BaseUrl . '/' . $model->image,
                [
                    'style' => 'height: 200px'
                ]
            ) . '<p>';
        }
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
