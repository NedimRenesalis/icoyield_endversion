<?php
    use yii\helpers\Html;
?>
<section class="simple-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <div class="block">
                    <div class="block-heading">
                        <div class="row">
                            <div class="col-lg-8 col-lg-push-2 col-md-8 col-md-push-2 col-sm-12 col-xs-12">
                                <h1>
                                    <?= Html::encode($page->title) ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="block-body">
                        <div class="row">
                            <div class="col-lg-8 col-lg-push-2 col-md-8 col-md-push-2 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <?= html_purify($page->content); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>