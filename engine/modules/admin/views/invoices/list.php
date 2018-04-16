<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = t('app', 'Invoices');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary invoice-index">

    <div class="box-header">
        <div class="pull-left">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="pull-right">
            <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-refresh fa-fw']) . t('app', 'Refresh'), ['index'], ['class' => 'btn btn-xs btn-success']) ?>
        </div>
    </div>

    <div class="box-body">
        <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'id'           => 'invoices',
            'tableOptions' => [
                'class' => 'table table-bordered table-hover table-striped',
            ],
            'options'          => ['class' => 'table-responsive grid-view'],
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                'invoice_id',
                'order_id',
                [
                    'format'    => 'ntext',
                    'attribute' => 'firstName',
                    'value'     => function ($model) {
                        return $model->order->first_name;
                    },
                ],
                [
                    'format'    => 'ntext',
                    'attribute' => 'lastName',
                    'value'     => function ($model) {
                        return $model->order->last_name;
                    },
                ],
                [
                    'format'    => 'ntext',
                    'attribute' => 'company',
                    'value'     => function ($model) {
                        return $model->order->company_name;
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
                    'template' => '{generate-pdf} {send-invoice}',
                    'buttons'  => [
                        'generate-pdf' => function ($url) {
                            return Html::a(
                                '<i class="fa fa-file-pdf-o"></i>',
                                $url,
                                [
                                    'data-pjax' => '0',
                                    'target'    => '_blank',
                                    'style'     => 'margin-right: 5px',
                                    'data-content'      => t('app', 'Generate PDF'),
                                    'data-container'    => 'body',
                                    'data-toggle'       => 'popover',
                                    'data-trigger'      => 'hover',
                                    'data-placement'    => 'top'
                                ]
                            );
                        },
                        'send-invoice' => function ($url) {
                            return Html::a(
                                '<i class="fa fa-envelope-o"></i>',
                                $url,
                                [
                                    'data-pjax' => '0',
                                    'data-content'      => t('app', 'Send invoice to client'),
                                    'data-container'    => 'body',
                                    'data-toggle'       => 'popover',
                                    'data-trigger'      => 'hover',
                                    'data-placement'    => 'top'
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
