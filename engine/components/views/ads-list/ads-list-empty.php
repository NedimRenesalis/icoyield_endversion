<section class="no-item">
    <div class="no-item-wrapper">
        <h1><?=t('app','Sorry');?> <span>!</span></h1>
        <h2><span><?=t('app','No ads');?></span> <?=t('app','were found here');?><span>.</span></h2>
        <p><?=t('app','Be the first to');?> <a href="<?=url(['/listing/post']);?>"><?=t('app', 'Post a New Ad');?></a> <?=t('app','on');?> <?=options()->get('app.settings.common.siteName', 'EasyAds');?></p>
    </div>
    <div class="tags"></div>
</section>