<?php

use yii\helpers\Html;
use yii\widgets\DetailView;


$this->title = t('app','Order') . ' #' . $model->order_id;
$this->params['breadcrumbs'][] = ['label' => t('app', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary orders-view">
    <div class="box-header">
        <div class="pull-left">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="pull-right">
        </div>
    </div>
    <div class="box-body">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'order_title',
                'order_id',
                [
                    'label' => 'Ad title',
                    'value' => ($model->listing_id) ? Html::a($model->ad->title, ['/admin/listings/admin-preview-ad', 'id' => $model->listing_id]) : t('app', 'Not Set'),
                    'format' => 'raw',
                ],
                [
                    'label' => 'Customer',
                    'value' => ($model->customer_id) ? Html::a($model->customer->first_name . ' ' . $model->customer->last_name, ['/admin/customers/view', 'id' => $model->customer_id]) : t('app', 'Not Set'),
                    'format' => 'raw',
                ],
                'first_name',
                'last_name',
                'company_name',
                'company_no',
                'vat',
                [
                    'label' => 'Country',
                    'value' => ($model->country_id) ? Html::a($model->country->name, ['/admin/countries/view', 'id' => $model->country_id]) : t('app', 'Not Set'),
                    'format' => 'raw',
                ],
                [
                    'label' => 'Zone',
                    'value' => ($model->zone_id) ? Html::a($model->zone->name, ['/admin/zones/view', 'id' => $model->zone_id]) : t('app', 'Not Set'),
                    'format' => 'raw',
                ],
                'city',
                'zip',
                'phone',
                'total',
                [
                    'label' => 'Status',
                    'value' => function($model){
                        return t('app',ucfirst(html_encode($model->status)));
                    }
                ],
                'created_at',
                'updated_at',
            ],
        ]) ?>
    </div>

</div>
