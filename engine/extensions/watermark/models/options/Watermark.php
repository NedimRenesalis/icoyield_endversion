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

namespace app\extensions\watermark\models\options;

use app\models\options\Base;
use Yii;
use yii\web\UploadedFile;

/**
 * Class AdBanners
 * @package app\extensions\watermark\models\options
 */
class Watermark extends Base
{

    /**
     * @var string
     */
    public $imageWatermarkUpload;

    /**
     * @var string
     */
    public $imageWatermark = '/uploads/images/site/watermark.png';

    /**
     * @var string
     */
    public $watermarkPrefix = '';

    /**
     * @var string
     */
    protected $categoryName = 'app.extensions.watermark';

    /**
     * @var
     */
    public $watermarkSize = '1';

    /**
     * @var
     */
    public $watermarkPosition = '0.1';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['imageWatermarkUpload'], 'file', 'extensions' => 'png'],
            [['imageWatermarkUpload', 'watermarkPrefix', 'watermarkSize', 'watermarkPosition'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'imageWatermarkUpload'      => t('app', 'Watermark Image'),
            'watermarkPosition'         => t('app', 'Watermark Position'),
            'watermarkSize'             => t('app', 'Watermark Size'),
        ];
    }

    /**
     * @return array
     */
    public function attributeHelpTexts()
    {
        return [
            'imageWatermarkUpload'      => t('app', 'Watermark Image'),
        ];
    }

    /**
     * @return array
     */
    public static function getWatermarkSizeList()
    {
        return [
            '0.1'    => t('app', '10 %'),
            '0.2'    => t('app', '20 %'),
            '0.5'    => t('app', '50 %'),
            '0.75'   => t('app', '75 %'),
        ];
    }

    /**
     * @return array
     */
    public static function getWatermarkPositionList()
    {
        return [
            '0'     => t('app', 'Center'),
            '1'     => t('app', 'Top-left'),
            '2'     => t('app', 'Top-right'),
            '3'     => t('app', 'Bottom-left'),
            '4'     => t('app', 'Bottom-right'),
        ];
    }

    /**
     * @param $watermarkWidth
     * @param $watermarkHeight
     * @param $imageWidth
     * @param $imageHeight
     * @return array
     */
    public static function getWatermarkPositionValue($watermarkWidth, $watermarkHeight, $imageWidth, $imageHeight) {
        $positionOption = options()->get('app.extensions.watermark.watermarkPosition','0.1');

        switch ((int)$positionOption) {
            case 0:
                $imageHeight = $imageHeight/2;
                $imageWidth = $imageWidth/2;

                $watermarkHeight = $watermarkHeight/2;
                $watermarkWidth = $watermarkWidth/2;

                $height = $imageHeight - $watermarkHeight;
                $width = $imageWidth - $watermarkWidth;

                $position = [$width, $height];

                return $position;
                break;
            case 1:
                $position = [0,0];
                return $position;
                break;
            case 2:
                $position = [$imageWidth - $watermarkWidth, 0];

                return $position;
                break;
            case 3:
                $position = [0, $imageHeight - $watermarkHeight];

                return $position;
                break;
            case 4:
                $position = [$imageWidth - $watermarkWidth, $imageHeight - $watermarkHeight];

                return $position;
                break;
            default:
                $position = [0,0];

                return $position;
                break;
        }

    }

    /**
     * manually saving attributes
     */
    public function afterValidate()
    {
        parent::afterValidate();
        $this->handleUploadedFiles();
    }


    /**
     *  saving the watermark
     */
    protected function handleUploadedFiles()
    {

        if ($this->hasErrors()) {
            return;
        }

        $dirtyAttributes = $this->getDirtyAttributes();

        if( isset($dirtyAttributes['watermarkPosition']) ||
            isset($dirtyAttributes['watermarkSize']) ) {
            $this->watermarkPrefix = substr(sha1(time()), 0, 6);
        }

        $storagePath = Yii::getAlias('@webroot/uploads/images/site');

        if (!file_exists($storagePath) || !is_dir($storagePath)) {
            if (!@mkdir($storagePath, 0777, true)) {
                $this->addError('logo', 'The images storage directory({path}) does not exists and cannot be created!'.$storagePath);
                return;
            }
        }

        foreach (['imageWatermarkUpload'] as $attribute) {
            if (!($file = UploadedFile::getInstance($this, $attribute))) {
                continue;
            }

            $newFileName = $file->name;
            $file->saveAs($storagePath . '/' . $newFileName);
            if (!is_file($storagePath . '/' . $newFileName)) {
                $this->addError('logo', 'Cannot move the Watermark Image into the correct storage folder!');
                continue;
            }

            $existing_file = $storagePath . '/' . $newFileName;
            $newFileName = substr(sha1(time()), 0, 6) . $newFileName;
            rename($existing_file, $storagePath . '/' . $newFileName);
            $attr = str_replace('Upload', '', $attribute);

            $oldWatermark = options()->get('app.extensions.watermark.imageWatermark','/uploads/images/site/watermark.png');
            $oldWatermark = basename($oldWatermark);

            if ($oldWatermark !== $newFileName) {
                $this->watermarkPrefix = substr(sha1(time()), 0, 6);
            }

            $this->$attr = Yii::getAlias('@web/uploads/images/site/' . $newFileName);

        }
    }
}