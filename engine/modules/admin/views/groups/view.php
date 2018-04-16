<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\jui\DatePicker;


$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => t('app', 'Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary groups-view">
    <div class="box-header">
        <div class="pull-left">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="pull-right">
            <?= Html::a(t('app', 'Update'), ['update', 'id' => $model->group_id], ['class' => 'btn btn-xs btn-success']) ?>
            <?= Html::a(t('app', 'Delete'), ['delete', 'id' => $model->group_id], [
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
                'group_id',
                'name',
                [
                    'attribute' => 'users',
                    'value' => count($model->users),
                ],
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

<div class="box box-primary groups-view">
    <div class="box-header">
        <div class="pull-left">
            <h3 class="box-title"><?= t('app', 'Users in this group'); ?></h3>
        </div>
    </div>
    <div class="box-body">
        <?php Pjax::begin([
            'enablePushState'=>true,
        ]); ?>
        <?= GridView::widget([
            'id' => 'users',
            'tableOptions' => [
                'class' => 'table table-bordered table-hover table-striped',
            ],
            'options'          => ['class' => 'table-responsive grid-view'],
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                'first_name',
                'last_name',
                'email:email',
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
                    'attribute'=>'status',
                    'value'=> function($model){
                        return t('app',ucfirst(html_encode($model->status)));
                    },
                    'filter' => Html::activeDropDownList($searchModel, 'status', [ 'active' => t('app','Active'), 'inactive' => t('app','Inactive'), ],['class'=>'form-control','prompt' => 'All'])
                ],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
