<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ConversationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Conversations');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary conversation-index">

    <div class="box-header">
        <div class="pull-left">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="pull-right">
            <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-trash fa-fw']) . t('app', 'Delete selected'), '#', [
                'class' => 'btn btn-xs btn-danger bulk-delete',
            ]) ?>
        </div>
    </div>

    <div class="box-body">
        <?php Pjax::begin([
            'id'      => 'conversation-pjax-container',
            'timeout' => 8000
        ]); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                ['class' => '\yii\grid\CheckboxColumn'],
                'conversation_id',
                [
                    'label'     => t('app', 'Listing'),
                    'attribute' => 'listing_id',
                    'value'     => function ($model) {
                        $listing = $model->listing;

                        return $listing ? $listing->title : t('app', 'No listing');
                    },
                ],
                [
                    'label'     => t('app', 'Seller'),
                    'attribute' => 'seller_id',
                    'value'     => function ($model) {
                        $seller = $model->seller;

                        return $seller ? $seller->fullName : t('app', 'No seller');
                    },
                ],
                [
                    'label'     => t('app', 'Buyer'),
                    'attribute' => 'buyer_id',
                    'value'     => function ($model) {
                        $buyer = $model->buyer;

                        return $buyer ? $buyer->fullName : t('app', 'No buyer');
                    },
                ],
                [
                    'attribute' => 'status',
                    'value'     => function($model) {
                        return t('app',ucfirst(html_encode($model->status)));
                    },
                    'filter'    => Html::activeDropDownList($searchModel, 'status', \app\models\Conversation::getStatusesList(), ['class' => 'form-control', 'prompt' => t('app', 'All')])
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
                    'template' => '{view} {delete}',
                    'contentOptions' => [
                        'style'=>'width:70px',
                        'class'=>'table-actions'
                    ],
                ],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
