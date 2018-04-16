<div class="box box-primary latest-listings">
    <div class="box-header with-border">
        <h3 class="box-title"><?=html_encode($title);?></h3>
    </div>
    <div class="box-body no-padding">
        <ul class="users-list clearfix">
            <?php foreach ($data as $item) {?>
            <li>
                <div class="img-bg" style="background-image: url('<?= ($item->mainImage != null) ? $item->mainImage->image : ''; ?>');"></div>
                <?php $formatter = app()->formatter; ?>
                <span class="users-list-date"><?= $formatter->asDatetime($item->created_at, 'short');?></span>
            </li>
            <?php } ?>
        </ul>
    </div>
    <div class="box-footer text-center">
        <a href="<?=$link;?>" class="uppercase"><?=t('app', 'View all items');?></a>
    </div>
</div>