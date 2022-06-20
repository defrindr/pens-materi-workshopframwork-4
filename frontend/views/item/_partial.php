<?php

use yii\bootstrap4\Html;
?>
<div class="card card-default mb-3">
    <div class="card-header">
        <h1><?= $model->name ?></h1>
        <h4>Rp <?= $model->price ?></h4>
    </div>
    <div class="card-body">
        <img src="<?= $model->getImageUrl() ?>" class="img img-fluid">
    </div>
    <div class="card-footer">
        <?= Html::a("Beli", ["beli", "id" => $model->id], ["class" => "btn btn-primary"]) ?>
    </div>
</div>