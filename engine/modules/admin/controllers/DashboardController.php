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


/**
 * Controls the actions for dashboard section
 *
 * @Class DashboardController
 * @package app\modules\admin\controllers
 */
class DashboardController extends \app\modules\admin\yii\web\Controller
{
    /**
     * Lists all widgets in dashboard
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'model' => '',
        ]);
    }

}
