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

namespace app\models;

use app\helpers\ImageHelper;
use app\helpers\UploadHelper;
use Yii;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Class ListingImage
 * @package app\models
 */
class ListingImage extends \app\models\auto\ListingImage
{
    /**
     * Image default width
     */
    CONST IMG_WIDTH = 2000;

    /**
     * Image default height
     */
    CONST IMG_HEIGHT = 2000;

    /**
     * @var
     */
    public $imagesGallery;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image_path'], 'required'],
            [['sort', 'listing_id', 'image_form_key'], 'safe'],
            ['imagesGallery', 'each', 'rule' => ['file', 'extensions' => 'png, jpg, jpeg']],
        ];
    }


    public function afterDelete()
    {
        $relativePath    = '/uploads/images/listings/' . (int)self::IMG_WIDTH . 'x' . (int)self::IMG_HEIGHT;
        $resizeBasePath        = \Yii::getAlias('@webroot' . $relativePath);
        $imagesPath = \Yii::getAlias('@webroot'. $this->image_path);
        $resizeImageName = basename($imagesPath);
        $resizeImagesPath = glob("$resizeBasePath/*$resizeImageName");
        foreach ($resizeImagesPath as $file) {
            unlink($file);
        }

        unlink($imagesPath);

        parent::afterDelete();
    }

    /**
     * @return bool|null|string
     */
    public function getImage()
    {
        return ImageHelper::resize($this->image_path, self::IMG_WIDTH, self::IMG_HEIGHT);
    }

    /**
     * @param $imagesGallery
     * @param $image_form_key
     * @param $adId
     * @param $sort
     * @return array
     * @throws \Exception
     */
    public function uploadImageByAjax($imagesGallery, $image_form_key, $adId, $sort)
    {
        if ((int)$adId === 0) { $adId = null; }

        /* set the output to json */
        response()->format = Response::FORMAT_JSON;

        $storagePath = Yii::getAlias('@webroot/uploads/images/listings');
        if (!file_exists($storagePath) || !is_dir($storagePath)) {
            if (!@mkdir($storagePath, 0777, true)) {
                throw new \Exception(t('app', 'The images storage directory({path}) does not exists and cannot be created!'. $storagePath));
            }
        }

        foreach ($imagesGallery as $imageGallery) {
            $sort++;

            $newGalleryImageName = $imageGallery->name;

            if(!$imageGallery->saveAs($storagePath . '/' . $newGalleryImageName)){
              throw new \Exception(UploadHelper::getErrorMessageFromErrorCode($imagesGallery->error));
            }
            if (!is_file($storagePath . '/' . $newGalleryImageName)) {
              throw new \Exception(t('app', 'Cannot move the image into the correct storage folder!'));
            }

            $existing_file = $storagePath . '/' . $newGalleryImageName;
            $newGalleryImageName = substr(sha1(time()), 0, 6) . $newGalleryImageName;
            rename($existing_file, $storagePath . '/' . $newGalleryImageName);

            $model = new static();
            $model->image_path = '/uploads/images/listings/' . $newGalleryImageName;
            $model->listing_id = $adId;
            $model->image_form_key = $image_form_key;
            $model->sort_order = $sort;

            $model->save();

            return [
                'initialPreview' => $model->image,
                'initialPreviewConfig' => [
                    ['caption' => $model->image,
                    'key' => $model->image_id,
                    'url' => url(['/listing/remove-ad-image'])
                    ]
                ]
            ];

        }

    }

    /**
     * @param $adId
     * @param $image_form_key
     * @throws \Exception
     */
    public function matchListingId($adId, $image_form_key)
    {
        $adId = (int)$adId;
        $existingImages = self::find()->where(['image_form_key' => $image_form_key])->all();

        if(empty($existingImages)){
            throw new \Exception(t('app', 'There are no images in the database with this random key'));
        }
        
        foreach($existingImages as $existingImage){
            $existingImage->listing_id = $adId;

            $existingImage->save();
        }
    }
}