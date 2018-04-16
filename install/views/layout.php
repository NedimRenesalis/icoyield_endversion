<?php defined('INST_INSTALLER_PATH') || exit('No direct script access allowed');

/**
 *
 * @package    EasyAds
 * @author     CodinBit <contact@codinbit.com>
 * @link       https://www.easyads.io
 * @copyright  2017 EasyAds (https://www.easyads.io)
 * @license    https://www.easyads.io
 * @since      1.0
 */

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>EasyAds | Installer</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" />
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css" />
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.6/css/AdminLTE.min.css" />
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.6/css/skins/_all-skins.min.css" />
        <link rel="stylesheet" type="text/css" href="../assets/css/site.css" />

        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.6/js/app.min.js"></script>
    </head>

    <body class="skin-blue layout-wide">
        <div class="wrapper">
            <header class="main-header">
                <a href="index.php?route=requirements" class="logo">
                    <span class="logo-mini"><b>Easy</b><br />Ads</span>
                    <span class="logo-lg"><b>Easy</b>Ads</span>
                </a>
                <nav class="navbar navbar-static-top">
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <div class="navbar-custom-menu">

                    </div>
                </nav>
            </header>
            <aside class="main-sidebar">
                <section class="sidebar">
                    <ul class="sidebar-menu">
                        <li class="<?php echo ($context instanceof WelcomeController) ? 'active':'';?>"><a href="javascript:;"><i class="glyphicon glyphicon-circle-arrow-right"></i> Welcome</a></li>
                        <li class="<?php echo ($context instanceof RequirementsController) ? 'active':'';?>"><a href="javascript:;"><i class="glyphicon glyphicon-circle-arrow-right"></i> Requirements</a></li>
                        <li class="<?php echo ($context instanceof FilesystemController) ? 'active':'';?>"><a href="javascript:;"><i class="glyphicon glyphicon-circle-arrow-right"></i> File system checks</a></li>
                        <li class="<?php echo ($context instanceof DatabaseController) ? 'active':'';?>"><a href="javascript:;"><i class="glyphicon glyphicon-circle-arrow-right"></i> Database import</a></li>
                        <li class="<?php echo ($context instanceof AdminController) ? 'active':'';?>"><a href="javascript:;"><i class="glyphicon glyphicon-circle-arrow-right"></i> Admin account</a></li>
                        <li class="<?php echo ($context instanceof CronController) ? 'active':'';?>"><a href="javascript:;"><i class="glyphicon glyphicon-circle-arrow-right"></i> Cron jobs</a></li>
                        <li class="<?php echo ($context instanceof FinishController) ? 'active':'';?>"><a href="javascript:;"><i class="glyphicon glyphicon-circle-arrow-right"></i> Finish</a></li>
                    </ul>
                </section>
            </aside>

            <div class="content-wrapper">
                <section class="content-header">
                    <h1><?php echo !empty($pageHeading) ? $pageHeading : '&nbsp;';?></h1>
                    <?php if (!empty($breadcrumbs)) { $bcount = count($breadcrumbs);?>
                        <ul class="breadcrumb">
                            <li><a href="index.php?route=requirements">Install</a><span class="divider"></span></li>
                            <?php $i = 0; foreach ($breadcrumbs as $text => $href) { ++$i; ?>
                                <li><a href="<?php echo $href;?>"><?php echo $text;?></a> <?php if ($i < $bcount) {?> <span class="divider"></span><?php }?></li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </section>
                <section class="content">
                    <?php if ($error = $context->getError('general')) { ?>
                        <div class="alert alert-danger alert-block">
                            <?php echo $error;?>
                            <button type="button" class="close" data-dismiss="alert">×</button>
                        </div>
                    <?php } ?>
                    {{CONTENT}}
                </section>
            </div>
            <footer class="main-footer"></footer>
        </div>
    </body>
</html>
