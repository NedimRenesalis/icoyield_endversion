<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;

$this->title = t('app', 'Orders');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary orders-index">

    <div class="box-header">
        <div class="pull-left">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="pull-right">
            <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-refresh fa-fw']) . t('app', 'Refresh'), ['index'], ['class' => 'btn btn-xs btn-success']) ?>
        </div>
    </div>

    <div class="box-body">
        <?php Pjax::begin([
            'enablePushState'=>true,
            ]); ?>
            <?= GridView::widget([
                'id' => 'orders',
                'tableOptions' => [
                    'class' => 'table table-bordered table-hover table-striped',
                ],
                'options'          => ['class' => 'table-responsive grid-view'],
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'order_id',
                    'first_name',
                    'last_name',
                    'company_name',
                    'total',
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
                     'value'=> function($model){
                         return t('app',ucfirst(html_encode($model->status)));
                     },
                     'filter' => Html::activeDropDownList($searchModel, 'status', \app\models\Order::getOrderStatusesList(),['class'=>'form-control','prompt' => 'All'])
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}{update}',
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
