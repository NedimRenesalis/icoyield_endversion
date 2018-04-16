<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MailAccount */

$this->title = $model->account_name;
$this->params['breadcrumbs'][] = ['label' => t('app', 'Mail Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary mail-account-view">
    <div class="box-header">
        <div class="pull-left">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="pull-right">
            <?= Html::a(t('app', 'Update'), ['update', 'id' => $model->account_id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(t('app', 'Delete'), ['delete', 'id' => $model->account_id], [
                'class' => 'btn btn-danger',
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
                    'value'     => function ($model) {
                        return implode(', ', \app\components\mail\template\TemplateType::getTypesList($model->used_for));
                    },
                ],
                'created_at',
                'updated_at',
            ],
        ]) ?>
    </div>
</div>
