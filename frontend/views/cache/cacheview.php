<?php if ($this->beginCache('cachedview')) { ?>
    <?php foreach ($models as $model) : ?>
        <?= $model->id; ?>
        <?= $model->username; ?>
        <?= $model->email; ?>
        <br />
    <?php endforeach; ?>
<?php $this->endCache();
} ?>
<?php echo "Count:", \common\models\User::find()->count(); ?>