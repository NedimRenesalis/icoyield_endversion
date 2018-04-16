<?php
use \yii\helpers\Html;
?>
<li class="image">
    <span class="img">
        <a href="<?=url(['/listing/index/' . $model->ad->slug]);?>">
            <img class="lazyload" src="<?=Yii::getAlias('@web/assets/site/img/img-listing-list-empty.png');?>" data-src="<?=($model->ad->mainImage) ? $model->ad->mainImage->image : '';?>" width="590" height="435" alt=""/>
        </a>
    </span>
</li>
<li class="name">
    <div class="truncate-ellipsis">
        <span><?= html_encode($model->ad->title); ?></span>
    </div>
</li>
<li class="date"><?= html_encode(app()->formatter->asDate($model->created_at));?></li>
<li class="actions icons">
    <?= Html::a(
        '<i class="fa fa-bookmark-o" aria-hidden="true"></i><span>' . t('app','Remove') . '</span>',
        ['#'],
        [
            'class'                 =>'btn-as danger-action delete-favorite-listing',
            'data-favorite-url'     => url(['/listing/toggle-favorite']),
            'data-listing-id'            => html_encode($model->listing_id),
            'data-pjax'             => 0
        ]
    );
    ?>
    <?= Html::a(
        '<i class="fa fa-eye" aria-hidden="true"></i><span>' . t('app','View') . '</span>',
        ['listing/index/' . html_encode($model->ad->slug)],
        [
            'class'=>'btn-as',
            'data-pjax' => 0
        ]
    );
    ?>
</li>