<?php

use yii\helpers\Html;
use yii\widgets\DetailView;


$this->title = $model->store_name;
$this->params['breadcrumbs'][] = ['label' => t('app', 'Stores'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary customer-stores-view">
    <div class="box-header">
        <div class="pull-left">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="pull-right">
            <?= Html::a(t('app', 'Update'), ['update', 'id' => $model->store_id], ['class' => 'btn btn-xs btn-success']) ?>
            <?= Html::a(t('app', 'Delete'), ['delete', 'id' => $model->store_id], [
                'class' => 'btn btn-xs btn-danger',
                'data' => [
                    'confirm' => t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>
    <div class="box-body">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'store_id',
                [
                    'attribute' => 'customer_id',
                    'value' => function($model) {
                        $customer = $model->customer;
                        return $customer ? $customer->fullName : 'Unknown';
                    },
                ],
                'store_name',
                'company_name',
                'company_no',
                'vat',
                'status',
                'created_at',
                'updated_at',
            ],
        ]) ?>
    </div>

</div>
