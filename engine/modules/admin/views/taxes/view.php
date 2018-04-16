<?php

use yii\helpers\Html;
use yii\widgets\DetailView;


$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => t('app', 'Taxes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary taxes-view">
    <div class="box-header">
        <div class="pull-left">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="pull-right">
            <?= Html::a(t('app', 'Update'), ['update', 'id' => $model->tax_id], ['class' => 'btn btn-xs btn-success']) ?>
            <?= Html::a(t('app', 'Delete'), ['delete', 'id' => $model->tax_id], [
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
                'tax_id',
                'name',
                'percent',
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
                'is_global',
                'status',
                'created_at',
                'updated_at',
            ],
        ]) ?>
    </div>

</div>
