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

namespace app\yii\web;

use app\helpers\CommonHelper;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\web\Controller as BaseController;

/**
 * Class Controller
 * @package app\yii\web
 */
class Controller extends BaseController
{

    /**
     * @param \yii\base\Action $action
     * @return bool|\yii\web\Response
     */
    public function beforeAction($action)
    {
        $bodyClasses = [
            options()->get('app.settings.theme.adminSkin', 'skin-blue'),
            options()->get('app.settings.theme.adminLayout', 'layout-wide'),
        ];


        if (options()->get('app.settings.theme.adminSidebar', 'sidebar-wide') == 'sidebar-mini') {
            $bodyClasses[] = 'sidebar-mini sidebar-collapse';
        }

        $customHead = options()->get('app.settings.seo.customHead', '');
        $customFooter = options()->get('app.settings.seo.customFooter', '');

        /* page/layout params */
        $this->setViewParams([
            'bodyClasses'    => implode(' ', $bodyClasses),
            'customHead'     => $customHead,
            'customFooter'     => $customFooter,
        ]);

        // add google analytics
        app()->on('app.header.beforeScripts', function() {
           return CommonHelper::getGoogleTrackingCode(html_encode(options()->get('app.settings.common.googleAnalyticsCode', '')));
        });

        //add possibility to create custom css and js
        app()->on('app.header.afterScripts', function()
        {
           echo options()->get('app.settings.theme.customCss', '');
           echo options()->get('app.settings.theme.customJs', '');
        });


        if((!empty($this->module) && $this->module->id != 'admin') &&
            ($this->route != 'site/offline' && options()->get('app.settings.common.siteStatus', 1) == 0) &&
            (app()->user->isGuest)
        ) {
            return $this->redirect(['/site/offline']);
        }

        return parent::beforeAction($action);
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setViewParams(array $params = [])
    {
        $this->view->params = ArrayHelper::merge($this->view->params, $params);
        return $this;
    }

}
