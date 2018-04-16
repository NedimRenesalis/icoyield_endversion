<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\jui\DatePicker;


$this->title = t('app', 'Ads Packages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary listings-packages-index">
    <div class="box-header">
        <div class="pull-left">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="pull-right">
            <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-plus fa-fw']) . t('app', 'Create Ads Package'), ['create'], ['class' => 'btn btn-xs btn-success']) ?>
            <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-refresh fa-fw']) . t('app', 'Refresh'), ['index'], ['class' => 'btn btn-xs btn-success']) ?>
        </div>
    </div>
    <div class="box-body">
        <?php Pjax::begin(['enablePushState'=>true]); ?>
            <?= GridView::widget([
                'id' => 'listings-packages',
                'tableOptions' => ['class' => 'table table-bordered table-hover table-striped'],
                'options'      => ['class' => 'table-responsive grid-view'],
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'title',
                    'price',
                    'listing_days',
                    'promo_days',
                    'auto_renewal',
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
                    ],
                ],
            ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
