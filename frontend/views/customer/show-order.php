<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;
?>
<div class="card card-default">
    <div class="card-body">
        <div class="table-responsive">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    // 'id',
                    'date',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{show}',
                        'buttons' => [
                            'show' => function ($url, $model) {
                                return Html::a('Show', ['order/view', 'id' => $model->id], [
                                    'title' => Yii::t('yii', 'Show'),
                                ]);
                            }
                        ],
                    ]
                ]
            ]); ?>
        </div>
    </div>
</div>