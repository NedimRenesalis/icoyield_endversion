<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
$this->title = t('app','Stats') . ': #' . $ad->listing_id;
$this->params['breadcrumbs'][] = ['label' => t('app', 'Ads'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary listings-stats-view">
    <div class="box-header">
        <div class="pull-left">
            <h3><?=$ad->title;?></h3>
        </div>
    </div>
    <div class="box-body">
        <?= DetailView::widget([
            'model' => $model,
            'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => 0],
            'attributes' => [
                'total_views',
                'facebook_shares',
                'twitter_shares',
                'mail_shares',
                'favorite',
                'show_phone',
                'show_mail',
            ],
        ]) ?>
    </div>
</div>
