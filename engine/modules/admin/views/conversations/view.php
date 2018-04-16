<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Conversation */

$this->title = $model->listing->title;
$this->params['breadcrumbs'][] = ['label' => t('app', 'Conversations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary conversation-view">
    <div class="box-header">
        <div class="pull-left">
            <h3 class="box-title"><?= t('app', 'Conversations related to ad') ?> "<?= Html::encode($this->title) ?>
                "</h3>
        </div>
        <div class="pull-right">
            <?= Html::a(t('app', 'Delete selected'), '#', [
                'class' => 'btn btn-danger bulk-delete-messages',
            ]) ?>
            <?= Html::a(t('app', 'Delete Conversation'), ['delete', 'id' => $model->conversation_id], [
                'class' => 'btn btn-danger',
                'data'  => [
                    'confirm' => t('app', 'Are you sure you want to delete this conversation?'),
                    'method'  => 'post',
                ],
            ]) ?>
        </div>
    </div>
    <div class="box-body">
        <?php Pjax::begin([
            'id'      => 'conversation-messages-pjax-container',
            'timeout' => 8000
        ]); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                ['class' => '\yii\grid\CheckboxColumn'],
                [
                    'label'  => t('app', 'Author'),
                    'filter' => Html::activeDropDownList($searchModel, 'customer', $model->participants, ['class' => 'form-control', 'prompt' => t('app', 'All')]),
                    'value'  => function ($model) {
                        $author = $model->getAuthor();

                        return html_encode($author->fullName);
                    },
                ],
                [
                    'label'     => t('app', 'Message'),
                    'attribute' => 'message',
                    'format'    => 'html',
                    'value'     => function ($model) {
                        return nl2br(html_purify(preg_replace("/[\r\n]+/", "\n", $model->message)));
                    },
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
                [
                    'class'    => 'yii\grid\ActionColumn',
                    'template' => '{delete}',
                    'buttons'  => [
                        'delete' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/admin/conversations/delete-message', 'messageId' => $model->conversation_message_id, 'conversationId' => $model->conversation_id], [
                                'title'      => t('app', 'Delete'),
                                'aria-label' => t('app', 'Delete'),
                                'data'       => [
                                    'confirm' => t('app', 'Are you sure you want to delete this item?'),
                                    'method'  => 'post',
                                    'pjax'    => 0
                                ],
                            ]);
                        }
                    ],
                    'contentOptions' => [
                        'style'=>'width:50px',
                        'class'=>'table-actions'
                    ],
                ],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
