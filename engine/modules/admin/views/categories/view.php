<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Categories */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => t('app', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary categoriess-view">
    <div class="box-header">
        <div class="pull-left">
            <h3 class="box-title"><i class="fa <?=$model->icon;?>" aria-hidden="true"></i> <?= Html::encode($this->title) ?></h3>
        </div>
        <div class="pull-right">
            <?= Html::a(t('app', 'Update'), ['update', 'id' => $model->category_id], ['class' => 'btn btn-xs btn-success']) ?>
            <?= Html::a(t('app', 'Delete'), ['delete', 'id' => $model->category_id], [
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
                'category_id',
                'name',
                'slug',
                [
                    'attribute'=>'parent.name',
                ],
                'description',
                [
                    'attribute' => 'status',
                    'value'     => function($model) {
                        return t('app',ucfirst(html_encode($model->status)));
                    },
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
            <h3 class="box-title"><?= t('app', 'Fields in this category'); ?></h3>
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
                'sort_order',
                'label',
                [
                    'label' => 'Field Type',
                    'attribute' => 'type_id',
                    'value' => function($model) {
                        $field_type = $model->type;
                        return $field_type ? $field_type->name : t('app','Unknown');
                    },
                ],
                'required',
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
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
