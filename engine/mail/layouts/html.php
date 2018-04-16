<?php

/**
 *
 * @package    EasyAds
 * @author     CodinBit <contact@codinbit.com>
 * @link       https://www.easyads.io
 * @copyright  2017 EasyAds (https://www.easyads.io)
 * @license    https://www.easyads.io
 * @since      1.0
 */

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */

$baseUrl = trim(str_replace('/index.php', '', options()->get('app.settings.urls.siteUrl', '')), '/');

?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=<?= app()->charset ?>" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Oswald:300,400,700" rel="stylesheet">
    <style type="text/css">
        #outlook a{
            padding: 0;
        }
        body{
            width: 100%!important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }
        img{
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
            image-rendering: optimizeQuality;
            display: block;
            max-width: 100%;
        }
        a img{
            border: none;
        }
        .bodyTbl{
            margin: 0;
            padding: 0;
            width: 100%!important;
            -webkit-text-size-adjust: none;
        }
        table.responsive-table {
            border-left: 1px solid #e1e1e1;
            border-right: 1px solid #e1e1e1;
        }
        @media screen and (max-width: 600px){
            table.responsive-table {
                width: 100%!important;
                border-left: 0!important;
                border-right: 0!important;
            }
        }
        @media screen and (max-width: 600px){
            table.responsive-table-inside {
                width:100% !important;
                -webkit-box-sizing: border-box !important;
                -moz-box-sizing: border-box !important;
                box-sizing: border-box !important;
                padding: 0 20px 0 20px !important;
            }
        }
        .header {
            min-height: 20px;
        }
        @media only screen and (-webkit-min-device-pixel-ratio: 2) {
            .header {
                background-image: url('<?= $baseUrl;?>/assets/site/img/bg-main-search@2x.png');
            }
        }
        .body {
            border-bottom: 1px solid #e1e1e1;
        }
        h1 {
            position: relative;
            /*font-family: 'Oswald', sans-serif;*/
            font-family: 'Impact', 'Charcoal', sans-serif;
            color: #009688;
            text-transform: uppercase;
            font-size: 24px;
            font-weight: normal!important;
            margin: 0 0 20px 0;
        }
        @media screen and (max-width: 600px){
            h1 {
                font-size: 18px;
            }
        }
        .footer {
            border-bottom: 1px solid #e1e1e1;
        }
    </style>
    <title><?= html_encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body style="background-color: #fafafa">
    <?php $this->beginBody() ?>
    <table class="bodyTbl" width="100%" cellspacing="0" cellpadding="0" bgcolor="#fafafa">
        <tr>
            <td align="center">
                <table width="600" cellspacing="0" cellpadding="0" class="header responsive-table">
                    <tr>
                        <td align="center" bgcolor="#009688">
                            &nbsp;
                        </td>
                    </tr>
                </table>
                    <?= $content ?>
                <table width="600" cellspacing="0" cellpadding="0" class="footer responsive-table" bgcolor="#ffffff">
                    <tr>
                        <td height="" align="center" bgcolor="#f7f7f7" style="border-bottom: 1px solid #e1e1e1;"></td>
                    </tr>
                    <tr><td height="35"></td></tr>
                    <tr>
                        <td align="center">
                            <font face="Open Sans, sans-serif" style="font-size:14px;" color="#000000">
                                <a href="<?= html_encode(options()->get('app.settings.urls.siteUrl', '#'));?>" style="color: #009688; text-decoration: none;"><?= html_encode(options()->get('app.settings.common.siteName', app_param('app.name')));?></a>
                            </font>
                            <br />
                            <font face="Open Sans, sans-serif" style="font-size:11px;" color="#000000">
                                &copy; <?= html_encode(options()->get('app.settings.common.siteName', 'EasyAds'));?> <?= date('Y');?>. <?=t('app','All rights reserved');?>.
                            </font>
                        </td>
                    </tr>
                    <tr><td height="35" style="border-bottom: 1px solid #e1e1e1;"></td></tr>
                </table>
            </td>
        </tr>
    </table>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
