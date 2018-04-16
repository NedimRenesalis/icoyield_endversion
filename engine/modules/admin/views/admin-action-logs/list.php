<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\jui\DatePicker;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AdminActionLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = t('app', 'Admin Action Logs');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary admin-action-log-index">

    <div class="box-header">
        <div class="pull-left">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="pull-right">
            <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-refresh fa-fw']) . t('app', 'Refresh'), ['index'], ['class' => 'btn btn-xs btn-success']) ?>
            <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-trash fa-fw']) . t('app', 'Clear logs'), ['clear'], [
                'class' => 'btn btn-xs btn-danger',
                'data'  => [
                    'confirm' => t('app', 'Are you sure you want to delete all logs?'),
                ],
                'data-content'      => t('app', 'This action will delete all the logs permanently'),
                'data-container'    => 'body',
                'data-toggle'       => 'popover',
                'data-trigger'      => 'hover',
                'data-placement'    => 'top'
            ]) ?>
        </div>
    </div>

    <div class="box-body">
        <?php Pjax::begin([
            'enablePushState' => true,
            'timeout'         => 10000
        ]); ?>
        <?= GridView::widget([
            'options'          => ['class' => 'table-responsive grid-view'],
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                [
                    'attribute' => 'controller_name',
                ],
                [
                    'attribute' => 'action_name',
                    'filter'    => Html::activeDropDownList(
                        $searchModel,
                        'action_name',
                        \app\models\AdminActionLog::getListOfLoggedActions(),
                        ['class' => 'form-control', 'prompt' => 'All']
                    ),
                ],
                'element',
                [
                    'format'    => 'ntext',
                    'attribute' => 'changed_data',
                    'value'     => function ($model) {
                        return StringHelper::truncate($model->changed_data, 40);
                    },
                ],
                [
                    'format'    => 'ntext',
                    'attribute' => 'changed_by',
                    'filter'    => Html::activeDropDownList(
                        $searchModel,
                        'changed_by',
                        \app\models\User::getListOfUsers(),
                        ['class' => 'form-control', 'prompt' => 'All']
                    ),
                    'value'     => function ($model) {
                        return ($model->changedBy != null) ? $model->changedBy->getFullName() : t('app', 'Visitor');
                    },
                ],
                [
                    'attribute' => 'created_at',
                    'filter'    => DatePicker::widget(
                        [
                            'model'      => $searchModel,
                            'attribute'  => 'created_at',
                            'options'    => [
                                'class' => 'form-control',
                            ],
                            'dateFormat' => 'yyyy-MM-dd',
                        ]
                    )
                ],
                [
                    'class'    => 'yii\grid\ActionColumn',
                    'template' => '{view}',
                ],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>