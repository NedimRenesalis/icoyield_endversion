<?php
use app\components\CategoriesListWidget;
use app\components\PageSectionWidget;
use app\components\SocialMediaListWidget;
use app\models\Page;
?>
<footer id="footer">

    <div class="post-add-bar">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                        <center>    <span class="pull-right"><?= t('app', 'Information hub for cryptofinance')?></span>
</center>   </div>
                        
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                     <center>       <a href="<?= url(['/listing/post']); ?>" class="btn-as secondary"><i class="fa fa-plus" aria-hidden="true"></i><?=t('app','Submit ICO');?></a>
</center>        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <center>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">

            </div>
        </div>
        <center>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <?= PageSectionWidget::widget(['sectionType' => Page::SECTION_ONE]) ?>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <?= PageSectionWidget::widget(['sectionType' => Page::SECTION_TWO]) ?>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <?= SocialMediaListWidget::widget(['title' => t('app', 'Connect')]) ?>
            </div>
        </div>
</center>
    </div>
    </center>
</footer>