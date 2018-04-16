<?php

use yii\helpers\Html;
use yii\widgets\DetailView;


$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => t('app', 'Zones'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary zones-view">
    <div class="box-header">
        <div class="pull-left">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="pull-right">
            <?= Html::a(t('app', 'Update'), ['update', 'id' => $model->zone_id], ['class' => 'btn btn-xs btn-success']) ?>
            <?= Html::a(t('app', 'Delete'), ['delete', 'id' => $model->zone_id], [
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
                'zone_id',
                [
                    'attribute' => 'country_id',
                    'value' => ($model->country_id) ? Html::a($model->country->name, ['/admin/countries/view', 'id' => $model->country_id]) : t('app', 'Not Set'),
                    'format' => 'raw',
                ],
                'name',
                'code',
                [
                     'attribute' => 'status',
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
