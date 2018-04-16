<?php

/**
 *
 * @package    EasyAds
 * @author     CodinBit <contact@codinbit.com>
 * @link       https://www.easyads.io
 * @copyright  2017 EasyAds (https://www.easyads.io)
 * @license    https://www.easyads.io
 * @since      1.3
 */

namespace app\extensions\watermark;

use Yii;
use app\helpers\CommonHelper;
use yii\imagine\Image;

use app\extensions\watermark\models\options\Watermark as OptionsWatermark;

/**
 * Class AdImageWatermark
 * @package app\extensions.watermark
 */
class Watermark extends \app\init\Extension
{

    public $name = 'Ads Image Watermark';

    public $author = 'CodinBit Development Team';

    public $version = '1.0';

    public $description = 'Adds watermark of ad images';

    public $type = 'tools';

    public function run()
    {
        // register controller
        app()->on('app.modules.admin.init', function($event) {
            $event->params['module']->controllerMap['ad-image-watermark'] = [
                'class' => 'app\extensions\watermark\admin\controllers\WatermarkController'
            ];
        });

        // event init
        app()->on('app.admin.menu', function($event) {
            $menu = $event->params['menu'];
            $arrayAux = CommonHelper::ArrayColumn($menu, 'label');
            $key = array_search('Miscellaneous', $arrayAux);
            $event->params['menu'][$key]['items'][] =
                ['label' => 'Watermark', 'icon' => 'chevron-right', 'url' => ['/admin/ad-image-watermark']];
        });

        // prefix for watermark
        app()->on('app.extensions.admin.watermarkPrefix', function ($event) {
            $enableWatermark = options()->get('app.extensions.watermark.status','disabled');

            $watermark = null;
            if (file_exists(Yii::getAlias('@webroot' . options()->get('app.extensions.watermark.imageWatermark', '/uploads/images/site/watermark.png')))) {
                $watermark = Yii::getAlias('@webroot' . options()->get('app.extensions.watermark.imageWatermark', '/uploads/images/site/watermark.png'));
            }

            $event->params['watermarkPrefix'] = null;
            if (!empty($watermark) && $enableWatermark === 'enabled' ) {
                $event->params['watermarkPrefix'] = (string)options()->get('app.extensions.watermark.watermarkPrefix', '') . '_';
            }
        });

        // apply the watermark on resized images.
        app()->on('app.extensions.admin.watermarkImage', function ($event) {
            $imageName = $event->params['imageName'];
            $basePath  = $event->params['basePath'];
            $prefix  = (string)options()->get('app.extensions.watermark.watermarkPrefix','') . '_';

            $imageWatermark = null;
            if (file_exists(Yii::getAlias('@webroot' . options()->get('app.extensions.watermark.imageWatermark', '/uploads/images/site/watermark.png')))) {
                $imageWatermark = Yii::getAlias('@webroot' . options()->get('app.extensions.watermark.imageWatermark', '/uploads/images/site/watermark.png'));
            }

            $enableWatermark = options()->get('app.extensions.watermark.status','disable');
            $watermarkSize = options()->get('app.extensions.watermark.watermarkSize','1');
            $imagePath = $basePath . '/' . $prefix .  $imageName;

            if ($imageWatermark !== null && $enableWatermark === 'enabled') {

                //ad image size
                $imageSize = getimagesize($imagePath);

                //watermark image size based on imageSize
                $watermarkWidth = $imageSize[0]*(float)$watermarkSize;
                $watermarkHeight = $imageSize[1]*(float)$watermarkSize;

                //get the exactly size of resize image for get correct position.
                $watermarkResize = Image::resize($imageWatermark, (float)$watermarkWidth, (float)$watermarkHeight, true, false)->getSize();
                $position = OptionsWatermark::getWatermarkPositionValue($watermarkResize->getWidth(), $watermarkResize->getHeight(), (float)$imageSize[0], (float)$imageSize[1]);

                $adWatermarkImage = Image::watermark($imagePath,  Image::resize($imageWatermark, (float)$watermarkWidth, (float)$watermarkHeight, true, false), [$position[0], $position[1]]);
                $adWatermarkImage->save(Yii::getAlias($imagePath));
            }
        });

    }
}