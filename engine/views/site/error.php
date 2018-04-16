<?php
use app\components\AdsListWidget;
$this->title = $name;
?>
<section class="not-found">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <div class="block">
                    <div class="block-heading">
                        <div class="row">
                            <div class="col-lg-8 col-lg-push-2 col-md-8 col-md-push-2 col-sm-12 col-xs-12">
                                <h1>
                                    <?= html_encode($this->title) ?>
                                    <span class="info"><?= nl2br(html_encode($message)) ?></span>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="others-listings">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-lg-push-2 col-md-12 col-sm-12 col-xs-12">
                <?= AdsListWidget::widget([
                    'listType'  => AdsListWidget::LIST_TYPE_PROMOTED,
                    'title'     => t('app', 'Similar ICOs'),
                    'quantity'  => 3
                ]);
                ?>
            </div>
        </div>
    </div>
</section>
