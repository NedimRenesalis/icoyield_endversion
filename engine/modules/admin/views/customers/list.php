<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;

$this->title = t('app', 'Customers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary customers-index">

    <div class="box-header">
        <div class="pull-left">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="pull-right">
            <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-plus fa-fw']) . t('app', 'Create Customer'), ['create'], ['class' => 'btn btn-xs btn-success']) ?>
            <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-refresh fa-fw']) . t('app', 'Refresh'), ['index'], ['class' => 'btn btn-xs btn-success']) ?>
        </div>
    </div>

    <div class="box-body">
        <?php Pjax::begin([
            'enablePushState'=>true,
            ]); ?>
            <?= GridView::widget([
                'id' => 'customers',
                'tableOptions' => [
                    'class' => 'table table-bordered table-hover table-striped',
                ],
                'options'          => ['class' => 'table-responsive grid-view'],
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'first_name',
                    'last_name',
                    'email',
                    'source',
                    'adsCount',
                    [
                     'attribute'=>'created_at',
                     'filter'=>  DatePicker::widget(
                         [
                            'model' => $searchModel,
                            'attribute' => 'created_at',
                            'options'=>[
                                'class'=>'form-control',
                                ],
                            'dateFormat' => 'yyyy-MM-dd',
                         ]
                     )
                    ],
                    [
                     'attribute'=>'status',
                     'value'=> function($model) {
                         return t('app',ucfirst(html_encode($model->status)));
                     },
                     'filter' => Html::activeDropDownList($searchModel, 'status', [ 'active' => t('app','Active'), 'inactive' => t('app','Inactive') ],['class'=>'form-control','prompt' => 'All'])
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'contentOptions' => [
                            'style'=>'width:145px',
                            'class'=>'table-actions'
                        ],
                        'template' => '{activate} {deactivate} {impersonate} {view} {update} {delete}',
                        'buttons'  => [
                            'activate' => function ($url, $model) {
                                return ($model->status === \app\models\Customer::STATUS_INACTIVE || $model->status === \app\models\Customer::STATUS_DEACTIVATED) ? Html::a(
                                    '<span class="fa fa-check"></span>',
                                    $url,
                                    [
                                        'data-content'      => t('app', 'Set active'),
                                        'data-container'    => 'body',
                                        'data-toggle'       => 'popover',
                                        'data-trigger'      => 'hover',
                                        'data-placement'    => 'top',
                                        'data-confirm'      => t('app', 'Are you sure you want to activate this item?'),
                                        'style'             => 'margin-right: 6px'
                                    ]
                                ) : '';
                            },
                            'deactivate' => function ($url, $model) {
                                return $model->status === \app\models\Customer::STATUS_ACTIVE ? Html::a(
                                    '<span class="fa fa-times"></span>',
                                    $url,
                                    [
                                        'data-content'      => t('app', 'Set inactive'),
                                        'data-container'    => 'body',
                                        'data-toggle'       => 'popover',
                                        'data-trigger'      => 'hover',
                                        'data-placement'    => 'top',
                                        'data-pjax'         => '0',
                                        'data-confirm'      => t('app', 'Are you sure you want to deactivate this item?'),
                                        'style'             => 'margin-right: 9px'
                                    ]
                                ) : '';
                            },
                            'impersonate' => function ($url, $model) {
                                return Html::a(
                                    '<i class="fa fa-random"></i>',
                                    url(['/admin/customers/impersonate', 'id' => $model->customer_id]),
                                    [
                                        'data-content'      => t('app', 'Impersonate this customer account on frontend'),
                                        'data-container'    => 'body',
                                        'data-toggle'       => 'popover',
                                        'data-trigger'      => 'hover',
                                        'data-placement'    => 'top',
                                        'data-pjax' => '0',
                                        'style'     => 'margin-right: 5px'
                                    ]
                                );
                            },
                        ],
                    ],
                ],
            ]); ?>
        <?php Pjax::end(); ?>
    </div>

</div>
