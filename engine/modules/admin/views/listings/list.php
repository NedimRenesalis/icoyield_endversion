<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\jui\DatePicker;


$this->title = t('app', 'Ads');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary listings-index">
    <div class="box-header">
        <div class="pull-left">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="pull-right">
            <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-refresh fa-fw']) . t('app', 'Refresh'), ['index'], ['class' => 'btn btn-xs btn-success']) ?>
        </div>
    </div>
    <div class="box-body">
        <?php Pjax::begin(['enablePushState'=>true]); ?>
        <?= GridView::widget([
            'id' => 'listings',
            'tableOptions' => ['class' => 'table table-bordered table-hover table-striped'],
            'options'          => ['class' => 'table-responsive grid-view'],
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                'title',
                [
                    'label' => t('app', 'Customer'),
                    'attribute' => 'customer_id',
                    'value' => function($model) {
                        $customer = $model->customer;
                        return $customer ? $customer->first_name.' '.$customer->last_name : t('app','No customer');
                    },
                ],
                [
                    'label' => t('app', 'Store'),
                    'attribute' => 'store',
                    'value' => function($model) {
                        $store = $model->store;
                        if($store && $store->status == \app\models\CustomerStore::STATUS_ACTIVE) {
                            return $store ? $store->store_name : t('app', 'No store');
                        }
                        return t('app', 'No store');
                    },
                ],
                [
                    'label' => t('app', 'Category'),
                    'attribute' => 'category_id',
                    'value' => function($model) {
                        $category = $model->category;
                        return $category ? $category->name : t('app','No category');
                    },
                ],
                [
                    'label' => t('app', 'Currency'),
                    'attribute' => 'currency_id',
                    'value' => function($model) {
                        $currency= $model->currency;
                        return $currency ? $currency->name : t('app','No currency');
                    },
                ],
                [
                    'label' => t('app', 'Location'),
                    'attribute' => 'location_id',
                    'value' => function($model) {
                        $location= $model->location;
                        return $location ? $location->city : t('app','No location');
                    },
                ],
                [
                    'label' => t('app', 'Package'),
                    'attribute' => 'package_id',
                    'value' => function($model) {
                        $package= $model->package;
                        return $package ? $package->title : t('app','No package');
                    },
                ],
                'remaining_auto_renewal',
                [
                    'label' => t('app', 'Status'),
                    'attribute' => 'status',
                    'value' => function($model) {
                            return t('app',ucfirst(html_encode($model->status)));
                    },
                ],
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
                    'class' => 'yii\grid\ActionColumn',
                    'contentOptions' => [
                        'style'=>'width:135px;',
                        'class'=>'table-actions'
                    ],
                    'template' => '{activate} {deactivate} {adminPreviewAd} {update} {adStats} {delete}',
                    'buttons' => [
                        'activate' => function ($url, $model) {
                            return ($model->status === \app\models\Listing::STATUS_PENDING_APPROVAL || $model->status === \app\models\Listing::STATUS_DEACTIVATED) ? Html::a(
                                '<span class="fa fa-check"></span>',
                                $url,
                                [
                                    'data-content'      => t('app', 'Activate'),
                                    'data-container'    => 'body',
                                    'data-toggle'       => 'popover',
                                    'data-trigger'      => 'hover',
                                    'data-placement'    => 'top',
                                    'data-confirm'      => t('app', 'Are you sure you want to activate this item?'),
                                    'data-pjax'         => '0',
                                ]
                            ) : '';
                        },
                        'deactivate' => function ($url, $model) {
                            return $model->status === \app\models\Listing::STATUS_ACTIVE ? Html::a(
                                '<span class="fa fa-times"></span>',
                                $url,
                                [
                                    'data-content'      => t('app', 'Deactivate'),
                                    'data-container'    => 'body',
                                    'data-toggle'       => 'popover',
                                    'data-trigger'      => 'hover',
                                    'data-placement'    => 'top',
                                    'data-pjax'         => '0',
                                    'data-confirm'      => t('app', 'Are you sure you want to deactivate this item?'),
                                    'style'             => 'margin-right: 3px'
                                ]
                            ) : '';
                        },
                        'adminPreviewAd' => function ($url, $model) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-eye-open"></span>',
                                url(['/admin/listings/admin-preview-ad', 'id' => $model->listing_id]),
                                [
                                    'data-content'      => t('app', 'Preview'),
                                    'data-container'    => 'body',
                                    'data-toggle'       => 'popover',
                                    'data-trigger'      => 'hover',
                                    'data-placement'    => 'top',
                                    'data-pjax'         => '0',

                                ]
                            );
                        },
                        'adStats' => function ($url, $model) {
                            return Html::a(
                                '<i class="fa fa-bar-chart"></i>',
                                url(['/admin/listings/stats', 'id' => $model->listing_id]),
                                [
                                    'data-content'      => t('app', 'Stats'),
                                    'data-container'    => 'body',
                                    'data-toggle'       => 'popover',
                                    'data-trigger'      => 'hover',
                                    'data-placement'    => 'top',
                                    'data-pjax'         => '0',
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
