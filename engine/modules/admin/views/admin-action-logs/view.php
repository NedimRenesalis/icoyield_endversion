<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AdminActionLog */

$this->title = $model->element;
$this->params['breadcrumbs'][] = ['label' => t('app', 'Admin Action Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary admin-action-log-view">
    <div class="box-header">
        <div class="pull-left">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
    </div>
    <div class="box-body">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'element',
                [
                    'attribute' => 'controller_name',
                ],
                [
                    'attribute' => 'action_name',
                ],
                'changed_model',
                'changed_data:ntext',
                [
                    'format'    => 'ntext',
                    'attribute' => 'changed_by',
                    'value'     => function ($model) {
                        return ($model->changedBy != null) ? $model->changedBy->getFullName() : t('app', 'Visitor');
                    },
                ],
                'created_at',
            ],
        ]) ?>
    </div>
</div>