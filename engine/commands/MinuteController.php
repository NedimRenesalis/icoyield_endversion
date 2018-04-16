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
 * This command is for minute cron
 *
 * Class MinuteController
 * @package app\commands
 */
class MinuteController extends Controller
{
    public function actionIndex()
    {
        app()->trigger('console.command.minute.start', new Event([
            'params' => [
                'controller' => $this
            ]
        ]));

        app()->consoleRunner->run('mail-system/index');
        app()->consoleRunner->run('generate-invoices/index');

        app()->trigger('console.command.minute.end', new Event([
            'params' => [
                'controller' => $this
            ]
        ]));
    }
}
