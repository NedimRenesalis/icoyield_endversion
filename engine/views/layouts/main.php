<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use app\assets\RtlAsset;
use app\helpers\LanguageHelper;
use app\yii\base\Event;

AppAsset::register($this);

if (LanguageHelper::getLanguageOrientation() == 'rtl') {
    RtlAsset::register($this);
}

$formatter = app()->formatter;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= app()->language ?>" dir="<?= LanguageHelper::getLanguageOrientation();?>">
    <head>
        <meta charset="<?= app()->charset ?>">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">

        <link rel="apple-touch-icon-precomposed" sizes="57x57" href="<?= \Yii::getAlias('@web/assets/common/favicon/apple-touch-icon-57x57.png');?>" />
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?= \Yii::getAlias('@web/assets/common/favicon/apple-touch-icon-114x114.png');?>" />
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?= \Yii::getAlias('@web/assets/common/favicon/apple-touch-icon-72x72.png');?>" />
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?= \Yii::getAlias('@web/assets/common/favicon/apple-touch-icon-144x144.png');?>" />
        <link rel="apple-touch-icon-precomposed" sizes="60x60" href="<?= \Yii::getAlias('@web/assets/common/favicon/apple-touch-icon-60x60.png');?>" />
        <link rel="apple-touch-icon-precomposed" sizes="120x120" href="<?= \Yii::getAlias('@web/assets/common/favicon/apple-touch-icon-120x120.png');?>" />
        <link rel="apple-touch-icon-precomposed" sizes="76x76" href="<?= \Yii::getAlias('@web/assets/common/favicon/apple-touch-icon-76x76.png');?>" />
        <link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?= \Yii::getAlias('@web/assets/common/favicon/apple-touch-icon-152x152.png');?>" />
        <link rel="icon" type="image/png" href="<?= \Yii::getAlias('@web/assets/common/favicon/favicon-196x196.png');?>" sizes="196x196" />
        <link rel="icon" type="image/png" href="<?= \Yii::getAlias('@web/assets/common/favicon/favicon-96x96.png');?>" sizes="96x96" />
        <link rel="icon" type="image/png" href="<?= \Yii::getAlias('@web/assets/common/favicon/favicon-32x32.png');?>" sizes="32x32" />
        <link rel="icon" type="image/png" href="<?= \Yii::getAlias('@web/assets/common/favicon/favicon-16x16.png');?>" sizes="16x16" />
        <link rel="icon" type="image/png" href="<?= \Yii::getAlias('@web/assets/common/favicon/favicon-128.png');?>" sizes="128x128" />
        <meta name="msapplication-TileColor" content="#FFFFFF" />
        <meta name="msapplication-TileImage" content="<?= \Yii::getAlias('@web/assets/common/favicon/mstile-144x144.png');?>" />
        <meta name="msapplication-square70x70logo" content="<?= \Yii::getAlias('@web/assets/common/favicon/mstile-70x70.png');?>" />
        <meta name="msapplication-square150x150logo" content="<?= \Yii::getAlias('@web/assets/common/favicon/mstile-150x150.png');?>" />
        <meta name="msapplication-wide310x150logo" content="<?= \Yii::getAlias('@web/assets/common/favicon/mstile-310x150.png');?>" />
        <meta name="msapplication-square310x310logo" content="<?= \Yii::getAlias('@web/assets/common/favicon/mstile-310x310.png');?>" />


        <?= Html::csrfMetaTags() ?>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Oswald:300,400,700" rel="stylesheet">
        <title><?= Html::encode($this->title, false) ?></title>
        <?php app()->trigger('app.header.beforeScripts', new Event(['params' => []])); ?>

        <script type="text/javascript">
            var site = {
                'url' : '<?=options()->get('app.settings.urls.siteUrl', '');?>',
                'language' : '<?=$formatter->locale;?>',
                'dateFormat' : '<?=html_encode($formatter->dateFormat);?>',
                'timeFormat' : '<?=$formatter->timeFormat;?>',
                'datetimeFormat' : '<?=html_encode($formatter->datetimeFormat);?>',
                'dir' : '<?= LanguageHelper::getLanguageOrientation();?>',
                'statsUrl' : '<?= url(['/listing/track-stats']);?>'
            };
        </script>
        <?php $this->head() ?>
        <?php app()->trigger('app.header.afterScripts', new Event(['params' => []])); ?>
    </head>
    <body>
        <div class="overlay-loading">
            <div class="icon-placeholder">
                <i class="fa fa-spinner fa-spin" aria-hidden="true"></i>
            </div>
        </div>

        <?= $this->render(
            'header.php',
            []
        ) ?>

        <?php $this->beginBody() ?>

        <div id="content">
            <?= $content; ?>
        </div>

        <?php $this->endBody() ?>

        <?= $this->render(
            'footer.php',
            []
        ) ?>
    </body>
</html>
<?php $this->endPage() ?>
