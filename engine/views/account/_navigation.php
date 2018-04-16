<?php
use app\helpers\SvgHelper;
$segment = app()->controller->action->id;

$navigation = [
        'my-listings' => [
            'url'   => 'account/my-listings',
            'label' => t('app', 'My ICO'),
        ],
        'favorites' => [
            'url'   => 'account/favorites',
            'label' => t('app', 'Favorite ICOs'),
        ],
        
       
        'info' => [
            'url'   => 'account/info',
            'label' => t('app', 'Account Info'),
        ],
    ];

//Event to modify array navigation.
$event = new \app\yii\base\Event();
$event->params = [
    'navigation' => $navigation,
];
app()->trigger('app.account.navigation.list', $event);

$accountNavigation = $event->params['navigation'];

?>

<section class="my-account-navigation">
    <h1><?=t('app', 'My Account');?></h1>
    <div class="myaccount-menu">
        <div id="account-nav">
            <?php foreach ($accountNavigation as $key => $value){?>
                <a href="<?= url([$value['url']]);?>" class="<?=($segment == $key) ? 'active' : '';?>"><?= $value['label'];?></a>
            <?php } ?>
        </div>
    </div>
    <div class="myaccount-menu-mobile">
        <div class="menu-subtitles">
            <?php foreach ($accountNavigation as $key => $value){ ?>
                <ul>
                    <li class="<?=($segment == $key) ? 'active' : '';?>">
                        <a href="javascript:;" data-toggle="collapse" data-target="#menu-options" aria-expanded="false">
                            <?= $value['label'];?><?= SvgHelper::getByName('arrow-top');?><?= SvgHelper::getByName('arrow-bottom');?>
                        </a>
                    </li>
                </ul>
            <?php } ?>
        </div>
        <div id="menu-options" class="menu-options collapse">
            <?php foreach ($accountNavigation as $key => $value){ ?>
                <ul>
                    <li class="<?=($segment == $key) ? 'active' : '';?>"><a href="<?= url([$value['url']]);?>"><?= $value['label'];?></a></li>
                </ul>
            <?php } ?>
        </div>
    </div>
</section>