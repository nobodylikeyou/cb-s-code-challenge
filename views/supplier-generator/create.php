<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Supplier $model */

$this->title = Yii::t('app', '新增供应商');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '供应商管理'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
