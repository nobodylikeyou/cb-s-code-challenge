<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Supplier $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="supplier-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 't_status')->dropDownList([ 'ok' => 'Ok', 'hold' => 'Hold', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
