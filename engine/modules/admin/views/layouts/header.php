<?php
use yii\helpers\Html;

$user = app()->user->identity;

?>
<header class="main-header">
    <?= Html::a('<span class="logo-mini"><i class="fa fa-cogs fa-fw" aria-hidden="true"></i></span><span class="logo-lg"><i class="fa fa-cogs" aria-hidden="true"></i> Admin</span>', app()->params['adminUrl'], ['class' => 'logo']) ?>
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle custom-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="admin-go-home">
                    <a href="<?=options()->get('app.settings.urls.siteUrl', '');?>" target="_blank" class="">
                        <span class=""><?=t('app', 'Go To Site');?></span>
                    </a>
                </li>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <?php $avatar = (is_file(\Yii::getAlias('@webroot') . $user['avatar'])) ? $user['avatar'] : Yii::getAlias('@web/assets/admin/img/default.jpg'); ?>
                        <img src="<?= $avatar;?>" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?= html_encode($user['first_name']) . ' ' . html_encode($user['last_name']);?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="<?= $avatar;?>" class="img-circle" alt="User Image"/>
                            <p>
                                <?= html_encode($user['first_name']) . ' ' . html_encode($user['last_name']);?>
                                <small><?=t('app', 'Member since');?> <?= date('M, Y',strtotime($user['created_at']));?></small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?=url(['/admin/users/view/', 'id'=>$user['id']]);?>" class="btn btn-default btn-flat"><?=t('app', 'Profile');?></a>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    'Sign out',
                                    ['/admin/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>