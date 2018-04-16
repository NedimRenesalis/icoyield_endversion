<?php
use yii\helpers\Html;
use app\assets\AdminAsset;

AdminAsset::register($this);

$directoryAsset = app()->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= app()->language ?>" dir="<?= \app\helpers\LanguageHelper::getLanguageOrientation();?>">
<head>
    <meta charset="<?= app()->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="<?= html_encode($this->params['bodyClasses']);?>">
<?php $this->beginBody() ?>
<div class="wrapper">

    <?= $this->render(
        'header.php',
        ['directoryAsset' => $directoryAsset]
    ) ?>

    <?= $this->render(
        'left.php',
        ['directoryAsset' => $directoryAsset]
    )
    ?>

    <?= $this->render(
        'content.php',
        ['content' => $content, 'directoryAsset' => $directoryAsset]
    ) ?>

</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
