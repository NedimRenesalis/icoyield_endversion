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

namespace app\modules\admin\yii\web;

use app\yii\web\Controller as BaseController;
use yii\helpers\Html;

/**
 * Class Controller
 * @package app\yii\web
 */
class Controller extends BaseController
{

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (user()->isGuest) {
            return response()->redirect(url(['/admin']))->send();
        }

        if (!user()->identity->canAccess($this->route)) {
            throw new \yii\web\ForbiddenHttpException(t('app','This action is not allowed!'));
        }

        // Show notification if email accounts are missing
        if (app()->mailSystem->getAccounts() == 0) {
            notify()->addWarning(t('app', 'Important: Email System in missing Email Accounts!'));
            notify()->addWarning(t('app', 'You will need to add at least one account and assign it to templates for the Email System to work properly.') . ' ' . Html::a(t('app','Fix Issue'),url(['/admin/mail-accounts'])));
        }

        // show notification if install folder is present
        $installPath = \Yii::getAlias('@webroot/install');
        if (file_exists($installPath) || is_dir($installPath)) {
            notify()->addError(t('app', 'Important note: Please remove install folder: {path}',['path' => $installPath]));
        }

        // show notification
        if (options()->get('app.settings.license.message', '') != '') {
            notify()->addError(options()->get('app.settings.license.message', ''));
        }

        // Default language for datepicker
        container()->set('yii\jui\DatePicker',[
           'language' => 'en-GB'
        ]);

        return parent::beforeAction($action);
    }

}
