<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\jui\DatePicker;


/* @var $this yii\web\View */
/* @var $searchModel app\models\MailQueueSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = t('app', 'Mail Queue');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary mail-queue-index">

    <div class="box-header">
        <div class="pull-left">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="pull-right">
            <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-trash fa-fw']) . t('app', 'Clear Queue'), ['delete-all'], ['class' => 'btn btn-xs btn-danger']) ?>
            <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-paper-plane-o fa-fw']) . t('app', 'Send Mail'), ['send-mail'], ['class' => 'btn btn-xs btn-primary']) ?>
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
                'id',
                'subject',
                [
                    'label'     => 'Template types',
                    'format'    => 'ntext',
                    'attribute' => 'message_template_type',
                    'filter'    => Html::activeDropDownList($searchModel, 'message_template_type', \app\components\mail\template\TemplateType::getTypesList(), ['class' => 'form-control', 'prompt' => 'All']),
                    'value'     => function ($model) {
                        return implode(', ', \app\components\mail\template\TemplateType::getTypesList($model->message_template_type));
                    },
                ],
                'attempts',
                [
                    'attribute' => 'last_attempt_time',
                    'filter'    => DatePicker::widget(
                        [
                            'model'      => $searchModel,
                            'attribute'  => 'last_attempt_time',
                            'options'    => [
                                'class' => 'form-control',
                            ],
                            'dateFormat' => 'yyyy-MM-dd',
                        ]
                    )
                ],
                [
                    'attribute' => 'time_to_send',
                    'filter'    => DatePicker::widget(
                        [
                            'model'      => $searchModel,
                            'attribute'  => 'time_to_send',
                            'options'    => [
                                'class' => 'form-control',
                            ],
                            'dateFormat' => 'yyyy-MM-dd',
                        ]
                    )
                ],
                [
                    'attribute' => 'created_at',
                    'filter'    => DatePicker::widget(
                        [
                            'model'      => $searchModel,
                            'attribute'  => 'created_at',
                            'options'    => [
                                'class' => 'form-control',
                            ],
                            'dateFormat' => 'yyyy-MM-dd',
                        ]
                    )
                ],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
