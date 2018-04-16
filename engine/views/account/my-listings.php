<?php
use app\assets\AccountAsset;
use app\helpers\SvgHelper;
AccountAsset::register($this);

echo $this->render('_navigation.php', []);
?>
<div class="container">
    <div class="row">
        <?php \yii\widgets\Pjax::begin([
            'enablePushState' => true,
        ]); ?>
        <?= \yii\widgets\ListView::widget([
            'id'           => 'my-listings',
            'dataProvider' => $myAdsProvider,
            'itemView'     => '_ad_item',
            'layout'       => '
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <ul class="list list-my-listings">
                            <li class="list-head">
                                <ul>
                                    <li class="image"></li>
                                    <li class="name">'.t('app','Name').'</li>
                                    <li class="category">'.t('app','Category').'</li>
                                    <li class="status">'.t('app','Status').'</li>
                                    <li class="actions icons"></li>
                                </ul>
                            </li>
                            <li class="list-body">
                                {items}
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="pagination-custom">
                            <div class="row">
                                {pager}
                            </div>
                        </div>
                    </div>
                ',
            'itemOptions'  => [
            ],
            'emptyText' => '
                <li class="list-head">
                    <ul>
                        <li class="number">' . t('app', 'My ICOs') . '</li>
                        <li class="date"></li>
                        <li class="total"></li>
                        <li class="actions"></li>
                    </ul>
                </li>
                <li class="list-body">
                    <ul>
                        <li class="empty">' . t('app', 'No results found') . '</li>
                    </ul>
                </li>',
            'emptyTextOptions' => ['tag' => 'ul', 'class' => 'list list-my-listings'],
            'pager'        => [
                'class'         => 'app\widgets\CustomLinkPager',
                'prevPageLabel' => SvgHelper::getByName('arrow-left'),
                'nextPageLabel' => SvgHelper::getByName('arrow-right')
            ],
        ]); ?>
        <?php \yii\widgets\Pjax::end(); ?>
    </div>
</div>

<div class="modal fade" id="modal-post-listing-delete" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-notice" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title"><?=t('app', 'Notice');?></h1>
                <a href="javascript:;" class="x-close" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></a>
            </div>
            <div class="modal-body">
                <p><?=t('app','Are you sure you want to delete this ICO?');?></p>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <button type="button" class="btn-as delete-listing" data-dismiss="modal"><?=t('app', 'Delete');?></button>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <button type="button" class="btn-as black pull-right" data-dismiss="modal"><?=t('app', 'Close');?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>