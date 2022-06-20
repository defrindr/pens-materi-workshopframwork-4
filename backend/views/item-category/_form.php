<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ItemCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="item-category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parent_category')->dropDownList(
        \yii\helpers\ArrayHelper::map(\common\models\ItemCategory::find()->all(), 'id', 'name'),
        ['prompt' => 'Select parent category']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>