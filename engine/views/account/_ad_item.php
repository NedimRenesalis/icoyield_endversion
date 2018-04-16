<ul>
    <li class="image">
        <span class="img">
            <a href="<?=url(['/listing/index/' . $model->slug]);?>">
                <img class="lazyload" src="<?=Yii::getAlias('@web/assets/site/img/img-listing-list-empty.png');?>" data-src="<?=($model->mainImage) ? $model->mainImage->image : '';?>" width="590" height="435" alt=""/>
            </a>
        </span>
    </li>
    <li class="name">
        <div class="truncate-ellipsis">
            <a href="<?=url(['/listing/index/' . $model->slug]);?>"><?= html_encode(strtoupper($model->title));?></a>
        </div>
    </li>
    <li class="category"><span><i class="fa <?=html_encode($model->category->icon);?>" aria-hidden="true"></i></span><span><?=html_encode($model->category->name);?></span></li>
    <li class="status"><?= t('app',ucfirst(html_encode($model->status)));?></li>
    <li class="actions icons">
        <?= \yii\helpers\Html::a(
            '<i class="fa fa-pencil" aria-hidden="true"></i><span>' . t('app','Edit') . '</span>',
            ['listing/update/' . html_encode($model->slug)],
            [
                'class'=>'btn-as',
                'data-pjax' => 0
            ]
            );
        ?>
        <?= \yii\helpers\Html::a(
            '<i class="fa fa-trash-o" aria-hidden="true"></i><span>' . t('app','Delete') . '</span>',
            ['#'],
            [
                'class'=>'btn-as danger-action delete-listing',
                'data-url' => url(['/listing/delete']),
                'data-listing-id' => html_encode($model->listing_id),
                'data-pjax' => 0
            ]
            );
        ?>
        <?= \yii\helpers\Html::a(
            '<i class="fa fa-area-chart" aria-hidden="true"></i><span>' . t('app','Stats') . '</span>',
            ['#stats-listing-' . html_encode($model->listing_id)],
            [
                'class'=>'btn-as transparent',
                'data-toggle' => 'collapse',
                'aria-controls' => 'stats-listing-' . html_encode($model->listing_id),
                'aria-expanded' => 'false'
            ]
        );
        ?>
    </li>
</ul>
<div id="stats-listing-<?=(int)$model->listing_id;?>" class="collapse stats-block">
    <div class="stats-item">
        <span class="heading"><?=t('app','Total views');?>:</span> <?=($model->stat) ? $model->stat->total_views : 0;?>
    </div>
    <div class="stats-item">
        <span class="heading"><?=t('app','Shared on');?>:</span>
        <i class="fa fa-facebook-f" aria-hidden="true"></i> <?=($model->stat) ? $model->stat->facebook_shares : 0;?>  &nbsp;&nbsp;&nbsp;
        <i class="fa fa-twitter" aria-hidden="true"></i> <?=($model->stat) ? $model->stat->twitter_shares : 0;?>  &nbsp;&nbsp;&nbsp;
        <i class="fa fa-envelope-o" aria-hidden="true"></i> <?=($model->stat) ? $model->stat->mail_shares : 0;?>
    </div>
    <div class="stats-item">
        <span class="heading"><?=t('app','Add to Favorites');?>:</span>
        <i class="fa fa-bookmark-o" aria-hidden="true"></i>
        <?=($model->stat) ? $model->stat->favorite : 0;?>
    </div>
    <div class="stats-item">
        <span class="heading"><?=t('app','Views on');?>:</span>
        <i class="fa fa-phone" aria-hidden="true"></i> <?=($model->stat) ? $model->stat->show_phone : 0;?>  &nbsp;&nbsp;&nbsp;
        <i class="fa fa-envelope-o" aria-hidden="true"></i> <?=($model->stat) ? $model->stat->show_mail : 0;?>
    </div>
</div>