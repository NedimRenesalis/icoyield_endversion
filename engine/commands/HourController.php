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

namespace app\commands;

use yii\console\Controller;
use \app\yii\base\Event;

/**
 * This command is for hour cron
 *
 * Class HourController
 * @package app\commands
 */
class HourController extends Controller
{

    public function actionIndex()
    {
        app()->trigger('console.command.hour.start', new Event([
            'params' => [
                'controller' => $this
            ]
        ]));

//        app()->consoleRunner->run('command-controller/index');

        app()->trigger('console.command.hour.end', new Event([
            'params' => [
                'controller' => $this
            ]
        ]));
    }
}
