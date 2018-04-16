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

use app\helpers\CommonHelper;
use app\models\Listing;
use app\models\options\Common;
use app\models\options\Email;
use app\models\options\License;
use app\models\options\Theme;
use app\models\options\Social;
use app\models\options\Invoice;
use yii\helpers\Url;
use yii\web\Response;

/**
 * Controls the actions for settings section
 *
 * @Class SettingsController
 * @package app\modules\admin\controllers
 */
class SettingsController extends \app\modules\admin\yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [];
    }

    /**
     * Controls general settings
     *
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        $model = new Common();

        if ($model->load(request()->post()) && $model->save()) {
            return $this->refresh();
        } else {
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Controls theme settings
     *
     * @return string|\yii\web\Response
     */
    public function actionTheme()
    {
        $model = new Theme();

        if ($model->load(request()->post()) && $model->save()) {
            return $this->refresh();
        } else {
            return $this->render('theme', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Controls social settings
     *
     * @return string|\yii\web\Response
     */
    public function actionSocial()
    {
        $model = new Social();

        if ($model->load(request()->post()) && $model->save()) {
            return $this->refresh();
        } else {
            return $this->render('social', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Controls invoice settings
     *
     * @return string|\yii\web\Response
     */
    public function actionInvoice()
    {
        $model = new Invoice();

        if ($model->load(request()->post()) && $model->save()) {
            return $this->refresh();
        } else {
            return $this->render('invoice', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Controls license settings
     *
     * @return string|Response
     */
    public function actionLicense()
    {
        $model = new License();

        if ($model->load(request()->post())) {
            $request = CommonHelper::verifyLicense($model);
            $error = '';

            if ($request['status'] == 'error') {
                $error = $request['message'];
            } else {
                $request = json_decode($request['message'], true);

                if (!empty($request)) {
                    $error = $request['message'];
                    options()->set('app.settings.license.message', $request['message']);
                }
            }

            if (empty($error)) {
                if (!$model->save()) {
                    notify()->addError(t('app', 'Your form contains a few errors, please fix them and try again!'));
                } else {
                    notify()->clearAll();
                    notify()->addSuccess(t('app', 'Your form has been successfully saved!'));
                    options()->set('app.settings.common.siteStatus', 1);
                    options()->set('app.settings.license.message', '');
                }
            } else {
                notify()->clearAll();
                notify()->addError($error);
            }
        }
        return $this->render('license', [
            'model' => $model,
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionHtaccessModal()
    {
        /* allow only ajax calls */
        if (!request()->isAjax) {
            return $this->redirect(['admin/index']);
        }

        return $this->renderPartial('_htaccess_modal',['controller'=>$this]);
    }

    /**
     * Tries to write the contents of the htaccess file
     */
    public function actionWriteHtaccess()
    {
        /* allow only ajax calls */
        if (!request()->isAjax) {
            return $this->redirect(['admin/index']);
        }

        response()->format = Response::FORMAT_JSON;

        if (!CommonHelper::isModRewriteEnabled()) {
            return array('result' => 'error', 'message' => t('app', 'Mod rewrite is not enabled on this host. Please enable it in order to use clean urls!'));
        }

        if (!is_file($file = \Yii::getAlias('@webroot') . '/.htaccess')) {
            if (!@touch($file)) {
                return array('result' => 'error', 'message' => t('app', 'Unable to create the file: {file}. Please create the file manually and paste the htaccess contents into it.', array('{file}' => $file)));
            }
        }

        if (!@file_put_contents($file, $this->getHtaccessContent())) {
            return array('result' => 'error', 'message' => t('app', 'Unable to write htaccess contents into the file: {file}. Please create the file manually and paste the htaccess contents into it.', array('{file}' => $file)));
        }

        return array('result' => 'success', 'message' => t('app', 'The htaccess file has been successfully created. Do not forget to save the changes!'));
    }

    /**
     * Will generate the contents of the htaccess file which later
     * should be written in the document root of the application
     */
    public function getHtaccessContent()
    {
        $webApps    = app()->modules;
        $baseUrl    = '/' . trim(Url::base(), '/') . '/';
        $baseUrl    = str_replace('//', '/', $baseUrl);

        return $this->renderPartial('_htaccess', ['webApps' => $webApps, 'baseUrl' => $baseUrl]);
    }

    /**
     * @return array|Response
     */
    public function actionDeleteExpiredAds(){

        /* allow only ajax calls*/
        if (!request()->isAjax) {
            return $this->redirect(['admin/settings/index']);
        }

        response()->format = Response::FORMAT_JSON;

        $date = app()->formatter->asDatetime(request()->post('date'), 'php:Y-m-d H-i-s');

        $expiredAds = Listing::find()
            ->where(['<', 'DATE(listing_expire_at)', $date])
            ->andWhere(['status' => Listing::STATUS_EXPIRED])
            ->each(50);

        $countAdsExpired = 0;
        foreach ($expiredAds as $ad) {
            $countAdsExpired += $ad->delete();
        }

        return ['result'=>'success', 'content' => $date, 'count' => $countAdsExpired];
    }
}
