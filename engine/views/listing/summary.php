<div class="row">
    <div class="col-lg-10 col-lg-push-1 col-md-10 col-md-push-1 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="separator-text">
                    <span><?=t('app','Summary');?></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="summary">
                    <ul>
                        <li><?=t('app','Subtotal');?>: <span><?=app()->formatter->asCurrency($subtotal);?></span></li>
                        <?php if ($tax) {
                            foreach ($tax as $t) { ?>
                                <li><?= t('app', 'Tax'); ?> - <?=html_encode($t->name) . ' (' . html_encode($t->percent) . '%)';?> <span><?=app()->formatter->asCurrency($t->price);?></span></li>
                        <?php }
                        }
                        ?>
                        <li><?=t('app','Total');?>: <span><?=app()->formatter->asCurrency($total);?></span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>