<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;


/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary users-index">

    <div class="box-header">
        <div class="pull-left">
            <h3 class="box-title"><i class="glyphicon glyphicon-user"></i> <?= Html::encode($this->title) ?></h3>
        </div>
        <div class="pull-right">
            <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-plus fa-fw']) . t('app', 'Create User'), ['create'], ['class' => 'btn btn-xs btn-success']) ?>
            <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-refresh fa-fw']) . t('app', 'Refresh'), ['index'], ['class' => 'btn btn-xs btn-success']) ?>
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
                        'attribute' => 'group_id',
                        'value' => function($model) {
                            $group = $model->group;
                            return $group ? $group->name : t('app',' No Group');
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
                        'attribute' => 'status',
                        'value'     => function($model) {
                            return t('app',ucfirst(html_encode($model->status)));
                        },
                        'filter'    => Html::activeDropDownList($searchModel, 'status', \app\models\Page::getStatusesList(), ['class' => 'form-control', 'prompt' => 'All'])
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
