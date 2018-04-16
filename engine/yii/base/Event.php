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

namespace app\yii\base;

use yii\base\Event as BaseEvent;

/**
* Class Event
* @package app\yii\base
*/
class Event extends BaseEvent
{
    public $params = [];
}