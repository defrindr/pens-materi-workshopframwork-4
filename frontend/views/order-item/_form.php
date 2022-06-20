<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OrderItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order_id')->dropDownList(
        ArrayHelper::map(
            \common\models\Order::find()->innerJoin('customer')->all(),
            'id',
            'id'
        ),
        ['prompt' => 'Select Order']
    ) ?>

    <?= $form->field($model, 'item_id')->dropDownList(
        ArrayHelper::map(
            \common\models\Item::find()->all(),
            'id',
            'name'
        ),
        ['prompt' => 'Select Item']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>