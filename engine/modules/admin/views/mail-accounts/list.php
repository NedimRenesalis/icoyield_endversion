<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MailAccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = t('app', 'Mail Accounts');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary mail-account-index">

    <div class="box-header">
        <div class="pull-left">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="pull-right">
            <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-plus fa-fw']) . t('app', 'Create Mail Account'), ['create'], ['class' => 'btn btn-xs btn-success']) ?>
            <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-refresh fa-fw']) . t('app', 'Refresh'), ['index'], ['class' => 'btn btn-xs btn-success']) ?>
        </div>
    </div>

    <div class="box-body">
        <?php Pjax::begin([
            'enablePushState'=>true,
        ]); ?>
        <?= GridView::widget([
            'options'          => ['class' => 'table-responsive grid-view'],
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                'account_name',
                'hostname',
                'username',
                'password',
                'port',
                'encryption',
                'timeout',
                'from',
                'reply_to',
                [
                    'label'     => 'Template types',
                    'format'    => 'ntext',
                    'attribute' => 'used_for',
                    'filter'    => Html::activeDropDownList($searchModel, 'used_for', \app\components\mail\template\TemplateType::getTypesList(), ['class' => 'form-control', 'prompt' => 'All']),
                    'value'     => function ($model) {
                        return implode(', ', \app\components\mail\template\TemplateType::getTypesList($model->used_for));
                    },
                ],

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
