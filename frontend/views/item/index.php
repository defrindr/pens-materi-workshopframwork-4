<?php

use common\models\ItemCategory;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\ListView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => Url::to(['item/index']),
        'options' => ['data-pjax' => true],
    ]); ?>
    <?= $form->field($searchModel, 'category_id')->dropdownList(
        ArrayHelper::map(ItemCategory::find()->all(), "id", "name"),
        ['prompt' => 'Select Category']
    )->label(false) ?>
    <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
    <?php ActiveForm::end() ?>

    <?= ListView::widget([
        'layout' => '
            <div class="col-md-12">
                <div class="card mb-2">
                    <div class="card-body">
                        {summary}
                    </div>
                </div>
            </div>
            <div class="clearfix"></div> 
                {items}
            <div class="clearfix"></div> 
            <div class="col-md-12">
                {pager}
            </div>',
        'options' => ['class' => 'row'],
        'dataProvider' => $dataProvider,
        'pager'        => [
            'class'          => LinkPager::className(),
            'firstPageLabel' => 'First',
            'lastPageLabel'  => 'Last'
        ],
        'itemOptions' => ['class' => 'col-md-4 col-sm-6'],
        'itemView' => '_partial'
    ]); ?>

    <?php Pjax::end(); ?>

</div>