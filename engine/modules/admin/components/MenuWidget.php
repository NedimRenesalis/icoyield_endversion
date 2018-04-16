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

namespace app\modules\admin\components;

use dmstr\widgets\Menu;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class MenuWidget
 * @package app\modules\admin\components
 */
class MenuWidget extends Menu
{

    /**
     * @param array $item
     * @return string
     */
    protected function renderItem($item)
    {
        if(isset($item['items'])) {
            $labelTemplate = '<a href="{url}">{icon} {label} <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
            $linkTemplate = '<a href="{url}">{icon} {label} <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
        }
        else {
            $labelTemplate = $this->labelTemplate;
            $linkTemplate = '<a target="{target}" href="{url}">{icon} {label}</a>';
        }

        if (isset($item['url'])) {
            $template = ArrayHelper::getValue($item, 'template', $linkTemplate);
            $replace = !empty($item['icon']) ? [
                '{url}' => Url::to($item['url']),
                '{target}' => (isset($item['target'])) ? $item['target'] : '',
                '{label}' => '<span>'.$item['label'].'</span>',
                '{icon}' => '<i class="' . self::$iconClassPrefix . $item['icon'] . '"></i> '
            ] : [
                '{url}' => Url::to($item['url']),
                '{target}' => Url::to($item['target']),
                '{label}' => '<span>'.$item['label'].'</span>',
                '{icon}' => $this->defaultIconHtml,
            ];
            return strtr($template, $replace);
        } else {
            $template = ArrayHelper::getValue($item, 'template', $labelTemplate);
            $replace = !empty($item['icon']) ? [
                '{label}' => '<span>'.$item['label'].'</span>',
                '{icon}' => '<i class="' . self::$iconClassPrefix . $item['icon'] . '"></i> '
            ] : [
                '{label}' => '<span>'.$item['label'].'</span>',
                '{icon}' => $this->defaultIconHtml
            ];
            return strtr($template, $replace);
        }
    }

}