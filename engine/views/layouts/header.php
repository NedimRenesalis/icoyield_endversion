<header id="header">
    <div class="header-wrapper">
        <?php if (session()->get('impersonating_customer')) {?>
        <section id="impersonating">
            You are now impersonating the customer <?= app()->customer->identity->getFullName();?><br />
            Click <a href="<?=url(['/account/impersonate']);?>" >here</a> to return to admin
        </section>
        <?php } ?>
        <section id="notify-container">
            <?= notify()->show();?>
        </section>
        <section class="main-menu">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="logo">
                            <a href="<?=url(['/']);?>">
                                <img src="<?=options()->get('app.settings.theme.siteLogo', \Yii::getAlias('@web/assets/site/img/logo.png'));?>" />
                            </a>
                            <a href="#nav-mobile" class="btn-as transparent mobile-menu pull-left hidden-lg hidden-md hidden-sm" data-toggle="collapse" aria-expanded="false" aria-controls="nav-mobile"><i class="fa fa-bars" aria-hidden="true"></i></a>
                            <a href="<?=url(['/listing/post']);?>" class="btn-add pull-right"><i class="fa fa-plus" aria-hidden="true"></i></a>
                        </div>
                        <div id="nav-mobile" class="collapse mobile-menu-wrapper">
                            <ul>
                                <?php if (app()->customer->isGuest == false) { ?>
                                <li><a href="<?=url(['account/info']);?>"><i class="fa fa-cog" aria-hidden="true"></i><?=t('app','Account Info');?></a></li>
                                <?php if (app()->customer->identity->group_id == 2) { ?>
                                    <li><a href="<?=url(['/store/index', 'slug' => app()->customer->identity->stores->slug]);?>"><i class="fa fa-eye" aria-hidden="true"></i><?=t('app','Cockpit');?></a></li>
                                <?php } else { ?>
                                    <?php if (options()->get('app.settings.common.disableStore', 0) == 0) { ?>
                                    <li><a href="<?=url(['account/info#company-block']);?>"><i class="fa fa-plus" aria-hidden="true"></i><?=t('app','Upgrade');?></a></li>
                                    <?php } ?>
                                <?php } ?>
                                <li><a href="<?=url(['account/my-listings']);?>"><i class="fa fa-tasks" aria-hidden="true"></i><?=t('app','My ICO');?></a></li>
                               
                                <li><a href="<?=url(['account/favorites']);?>"><i class="fa fa-folder-o" aria-hidden="true"></i><?=t('app','Bookmarked');?></a></li>
                                <?php if (options()->get('app.settings.invoice.disableInvoices', 0) == 0){ ?>
                                    <li><a href="<?=url(['account/invoices']);?>"><i class="fa fa-file-o" aria-hidden="true"></i><?=t('app','Invoices');?></a></li>
                                <?php } ?>
                                <li><a href="<?=url(['account/logout']);?>"><i class="fa fa-power-off" aria-hidden="true"></i><?=t('app','Logout');?></a></li>
                                <?php } else { ?>
                                <li><a href="<?=url(['account/join']);?>"><i class="fa fa-cube" aria-hidden="true"></i><?=t('app','Register');?></a></li>
                                <li><a href="<?=url(['account/login']);?>"><i class="fa fa-hdd-o" aria-hidden="true"></i><?=t('app','Login');?></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 hidden-xs">
                        <div class="main-navigation">
                            <!-- -->
                            <div class="btn-group hidden-xs">
                                <a href="javascript:;" class="btn-as transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <i class="fa fa-cubes" aria-hidden="true"></i><?=t('app','Dashboard');?> 
                                </a>
                                <div class="dropdown-menu">
                                    <?php if (app()->customer->isGuest == false) { ?>
                                        <a href="<?=url(['account/info']);?>"><i class="fa fa-cog" aria-hidden="true"></i><?=t('app','Account Info');?></a>
                                        <?php if (app()->customer->identity->group_id == 2) { ?>
                                            <a href="<?=url(['/store/index', 'slug' => app()->customer->identity->stores->slug]);?>"><i class="fa fa-eye" aria-hidden="true"></i><?=t('app','Cockpit');?></a>
                                        <?php } else { ?>
                                            <?php if (options()->get('app.settings.common.disableStore', 0) == 0) { ?>
                                                <a href="<?=url(['account/info#company-block']);?>"><i class="fa fa-plus" aria-hidden="true"></i><?=t('app','Upgrade');?></a>
                                            <?php } ?>
                                        <?php } ?>
                                        <a href="<?=url(['account/my-listings']);?>"><i class="fa fa-tasks" aria-hidden="true"></i><?=t('app','My ICO');?></a>
                                    
                                        <a href="<?=url(['account/favorites']);?>"><i class="fa fa-folder-o" aria-hidden="true"></i><?=t('app','Bookmarked');?></a>
                                        <?php if (options()->get('app.settings.invoice.disableInvoices', 0) == 0){ ?>
                                            <a href="<?=url(['account/invoices']);?>"><i class="fa fa-file-o" aria-hidden="true"></i><?=t('app','Invoices');?></a>
                                        <?php } ?>
                                        <a href="<?=url(['account/logout']);?>"><i class="fa fa-power-off" aria-hidden="true"></i><?=t('app','Logout');?></a>
                                    <?php } else { ?>
                                        <a href="<?=url(['account/join']);?>"><i class="fa fa-cube" aria-hidden="true"></i><?=t('app','Register');?></a>
                                        <a href="<?=url(['account/login']);?>"><i class="fa fa-hdd-o" aria-hidden="true"></i><?=t('app','Login');?></a>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="btn-group hidden-xs">
                                <a href="<?=url(['/listing/post']);?>" class="btn-as reverse"><i class="fa fa-plus" aria-hidden="true"></i><?=t('app','Submit ICO');?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</header>