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

namespace app\modules\admin\controllers;


use twisted1919\helpers\Common as CommonHelper;
use twisted1919\helpers\FileHelper;
use yii\web\Response;

/**
 * Control the access to Upgrade process
 *
 * @Class UpgradeController
 * @package app\controllers
 */
class UpgradeController extends \app\modules\admin\yii\web\Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        /* if already at the latest version */
        $version = options()->get('app.data.version', '1.0');
        if (version_compare($version, APP_VERSION, '>=')) {
            /* inform about the action */
            notify()->addInfo(t('app', 'You are already at the latest version!'));
            return $this->redirect(['dashboard/index']);
        }

        /* get the migration object */
        $migration = app()->migration;

        /* if the upgrade process is initialised */
        if (request()->isPost) {

            /* apply all the migrations */
            $migration->up(0);

            /* if no error inform about success, otherwise show in view what happened */
            if (!$migration->error) {

                /* set the new application version */
                options()->set('app.data.version', APP_VERSION);

                /* inform about the action */
                notify()->addSuccess(t('app', 'Congratulations, your application has been successfully upgraded to version {version}', [
                    'version' => '<span class="badge">' . APP_VERSION . '</span>',
                ]));

                /* flush the caches */
                cache()->flush();

                /* empty the runtime folder */
                FileHelper::deleteDirectoryContents(\Yii::getAlias('@app/runtime'), true);

                /* empty the assets cache */
                FileHelper::deleteDirectoryContents(\Yii::getAlias('@webroot/assets/cache'), true);

                /* empty the assets twig cache */
                FileHelper::deleteDirectoryContents(\Yii::getAlias('@webroot/assets/twig/cache'), true);

                /* put back the .gitignore file */
                $gitignore = file_get_contents(\Yii::getAlias('@app/data/gitignore-all-but-it.txt'));
                file_put_contents(\Yii::getAlias('@app/runtime/.gitignore'), $gitignore);
                file_put_contents(\Yii::getAlias('@webroot/assets/cache/.gitignore'), $gitignore);
                file_put_contents(\Yii::getAlias('@webroot/assets/twig/cache/.gitignore'), $gitignore);

                $license = options()->get('app.settings.license.purchaseCode', '');
                $licenseEmail = options()->get('app.settings.license.email', '');
                if (empty($license) || empty($licenseEmail)) {
                    return $this->redirect(['settings/license']);
                }
                /* back to dashboard, we are done! */
                return $this->redirect(['dashboard/index']);
            }
        }

        /* notes */
        notify()->addInfo(t('app', 'Please note, depending on your database size it is better to run the command line upgrade tool instead.'));
        notify()->addInfo(t('app', 'In order to run the command line upgrade tool, you must run the following command from a ssh shell:'));
        notify()->addInfo(sprintf('<strong>%s</strong>', CommonHelper::findPhpCliPath() . ' ' . \Yii::getAlias('@webroot/index.php') . ' upgrade'));

        /* view params */
        $this->setViewParams([
            'pageTitle'      => view_param('pageTitle') . ' | ' . t('app', 'Upgrade application'),
            'pageHeading'    => t('app', 'Upgrade application'),
            'pageSubHeading' => t('app', 'Overview'),
            'pageBreadcrumbs'=> [
                t('app', 'Overview'),
            ],
        ]);

        /* render the view */
        return $this->render('index', [
            'migration' => $migration,
            'version'   => $version
        ]);
    }
}
