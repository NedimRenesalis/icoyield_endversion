<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;


$this->title = t('app', 'Pages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary pages-index">

    <div class="box-header">
        <div class="pull-left">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="pull-right">
            <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-plus fa-fw']) . t('app', 'Create Page'), ['create'], ['class' => 'btn btn-xs btn-success']) ?>
            <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-refresh fa-fw']) . t('app', 'Refresh'), ['index'], ['class' => 'btn btn-xs btn-success']) ?>
        </div>
    </div>

    <div class="box-body">
        <?php Pjax::begin([
            'enablePushState' => true,
            'timeout'         => 10000
        ]); ?>
        <?= GridView::widget([
            'id' => 'pages',
            'tableOptions' => [
                'class' => 'table table-bordered table-hover table-striped',
            ],
            'options'          => ['class' => 'table-responsive grid-view'],
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                'title',
                'slug',
                [
                    'attribute' => 'status',
                    'value'     => function($model) {
                        return t('app',ucfirst(html_encode($model->status)));
                    },
                    'filter'    => Html::activeDropDownList($searchModel, 'status', \app\models\Page::getStatusesList(), ['class' => 'form-control', 'prompt' => 'All'])
                ],
                [
                    'attribute' => 'section',
                    'value'     => function ($model) {
                        return $model->getSection();
                    },
                    'filter'    => Html::activeDropDownList($searchModel, 'section', \app\models\Page::getSectionsList(), ['class' => 'form-control', 'prompt' => 'All'])
                ],
                'sort_order',
                [
                    'attribute'=>'created_at',
                    'filter'=>  DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'created_at',
                        'options'=>[
                            'class'=>'form-control',
                        ],
                        'dateFormat' => 'yyyy-MM-dd',
                    ])
                ],
                [
                    'class'          => 'yii\grid\ActionColumn',
                    'contentOptions' => [
                        'style' => 'width:100px',
                        'class' => 'table-actions'
                    ],
                    'buttons'        => [
                        'view' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', app()->urlManager->createUrl(['pages/index', 'slug' => $model->slug]), [
                                'title'     => t('app', 'Preview'),
                                'target'    => '_blank',
                                'data-pjax' => '0'
                            ]);
                        },
                    ]
                ],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>

</div>
