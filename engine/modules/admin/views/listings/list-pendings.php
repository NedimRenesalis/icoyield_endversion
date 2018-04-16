<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\jui\DatePicker;


$this->title = t('app', 'Pending Ads');
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
            'id' => 'Pending Ads',
            'tableOptions' => ['class' => 'table table-bordered table-hover table-striped'],
            'options'          => ['class' => 'table-responsive grid-view'],
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'label'     => t('app','Ad title'),
                    'attribute' => 'title'
                ],
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
                        if(!empty($store) && $store->status == \app\models\CustomerStore::STATUS_ACTIVE) {
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
                    'label'     => t('app','Ad price'),
                    'attribute' => 'price'
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
                [
                    'label'     => t('app','Remaining Auto Renewal'),
                    'attribute' => 'remaining_auto_renewal'
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
                        'style'=>'width:100px',
                        'class'=>'table-actions'
                    ],
                    'template' => '{activate} {deactivate} <br> {adminPreviewAd}',
                    'buttons' => [
                        'activate' => function ($url, $model) {
                            return ($model->status === \app\models\Listing::STATUS_PENDING_APPROVAL || $model->status === \app\models\Listing::STATUS_DEACTIVATED) ? Html::a(
                                t('app', 'Activate'),
                                $url,
                                [
                                    'title' => t('app', 'Activate'),
                                    'data-pjax' => '0',
                                ]
                            ) : '';
                        },
                        'deactivate' => function ($url, $model) {
                            return $model->status === \app\models\Listing::STATUS_ACTIVE ? Html::a(
                                t('app', 'Deactivate'),
                                $url,
                                [
                                    'title' => t('app', 'Deactivate'),
                                    'data-pjax' => '0',
                                ]
                            ) : '';
                        },
                        'adminPreviewAd' => function ($url, $model) {
                            return Html::a(
                                t('app', 'Preview'),
                                url(['/admin/listings/admin-preview-ad', 'id' => $model->listing_id]),
                                [
                                    'title'     => 'Preview',
                                    'data-pjax' => '0',
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
