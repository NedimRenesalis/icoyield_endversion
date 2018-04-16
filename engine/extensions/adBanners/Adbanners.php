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

namespace app\extensions\adBanners;

use app\extensions\adBanners\helpers\AdBannersHelper;
use app\helpers\CommonHelper;
use yii\helpers\ArrayHelper;

/**
 * Class AdBanners
 * @package app\extensions\adBanners
 */
class Adbanners extends \app\init\Extension
{

    public $name = 'Advertising Banners';

    public $author = 'CodinBit Development Team';

    public $version = '1.0';

    public $description = 'Adds banners of advertising in pages';

    public $type = 'tools';

    public function run()
    {

        // register controller
        app()->on('app.modules.admin.init', function($event) {
            $event->params['module']->controllerMap['ad-banners'] = [
                'class' => 'app\extensions\adBanners\admin\controllers\AdBannersController'
            ];
        });

        // event init
        app()->on('app.admin.menu', function($event) {
            $menu = $event->params['menu'];
            $arrayAux = CommonHelper::ArrayColumn($menu, 'label');
            $key = array_search('Miscellaneous', $arrayAux);
            $event->params['menu'][$key]['items'][] =
                ['label' => 'Advertising', 'icon' => 'chevron-right', 'url' => ['/admin/ad-banners']];
        });

        app()->on('app.header.beforeScripts', function($event) {
            echo options()->get('app.extensions.adBanners.headScripts', '');
        });

        $locations = AdBannersHelper::getStaticAdBannersProperties();
        foreach ($locations as $location) {
            app()->on($location['event'], function($event) use ($location){
                echo options()->get('app.extensions.adBanners.' . $location['optionKey'], '');
            });
        }
    }
}