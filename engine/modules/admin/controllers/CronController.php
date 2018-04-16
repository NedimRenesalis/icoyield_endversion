<?php

/**
 *
 * @package    EasyAds
 * @author     CodinBit <contact@codinbit.com>
 * @link       https://www.easyads.io
 * @copyright  2017 EasyAds (https://www.easyads.io)
 * @license    https://www.easyads.io
 * @since      1.0.1
 */

namespace app\modules\admin\controllers;

/**
 * Controls the actions for cron section
 *
 * @Class CronController
 * @package app\modules\admin\controllers
 */
class CronController extends \app\modules\admin\yii\web\Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', []);
    }
}
