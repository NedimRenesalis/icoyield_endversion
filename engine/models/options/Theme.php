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

namespace app\models\options;

use Yii;
use yii\web\UploadedFile;

/**
 * Class Theme
 * @package app\models\options
 */
class Theme extends Base
{
    /**
     * @var string
     */
    public $adminSkin = 'skin-blue';

    /**
     * @var string
     */
    public $adminLayout = 'layout-wide';

    /**
     * @var string
     */
    public $adminSidebar = 'sidebar-wide';

    /**
     * @var
     */
    public $siteLogo;

    /**
     * @var
     */
    public $siteLogoUpload;

    /**
     *
     */
    public $customCss = '';

    /**
     *
     */
    public $customJs = '';

    /**
     * @var string
     */
    protected $categoryName = 'app.settings.theme';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['adminSkin', 'adminLayout', 'adminSidebar'], 'required'],
            ['adminSkin', 'in', 'range' => array_keys($this->getSkinsList())],
            ['adminLayout', 'in', 'range' => array_keys($this->getLayoutsList())],
            ['adminSidebar', 'in', 'range' => array_keys($this->getSidebarsList())],
            [['siteLogoUpload'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['siteLogoUpload', 'customCss', 'customJs'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'adminSkin'         => t('app', 'Skin'),
            'adminLayout'       => t('app', 'Layout'),
            'adminSidebar'      => t('app', 'Sidebar'),
            'siteLogo'          => t('app', 'Logo'),
            'siteLogoUpload'    => t('app', 'Logo'),
            'customCss'         => t('app', 'Custom CSS'),
            'customJs'          => t('app', 'Custom JS')
        ];
    }

    /**
     * @return array
     */
    public function getSkinsList()
    {
        return [
            'skin-red'          => t('app', 'Red'),
            'skin-blue'         => t('app', 'Blue'),
            'skin-black'        => t('app', 'Black'),
            'skin-purple'       => t('app', 'Purple'),
            'skin-green'        => t('app', 'Green'),
            'skin-yellow'       => t('app', 'Yellow'),
            'skin-red-light'    => t('app', 'Red light'),
            'skin-blue-light'   => t('app', 'Blue light'),
            'skin-black-light'  => t('app', 'Black light'),
            'skin-purple-light' => t('app', 'Purple light'),
            'skin-green-light'  => t('app', 'Green light'),
            'skin-yellow-light' => t('app', 'Yellow light'),
        ];
    }

    /**
     * @return array
     */
    public function getLayoutsList()
    {
        return [
            'layout-boxed' => t('app', 'Boxed'),
            'layout-wide'  => t('app', 'Wide'),
        ];
    }

    /**
     * @return array
     */
    public function getSidebarsList()
    {
        return [
            'sidebar-mini' => t('app', 'Mini'),
            'sidebar-wide' => t('app', 'Wide'),
        ];
    }

    /**
     *
     */
    public function afterValidate()
    {
        parent::afterValidate();
        $this->handleUploadedFiles();
    }

    /**
     *
     */
    protected function handleUploadedFiles()
    {

        if ($this->hasErrors()) {
            return;
        }

        $storagePath = Yii::getAlias('@webroot/uploads/images/site');

        if (!file_exists($storagePath) || !is_dir($storagePath)) {
            if (!@mkdir($storagePath, 0777, true)) {
                $this->addError('logo', 'The images storage directory({path}) does not exists and cannot be created!'.$storagePath);
                return;
            }
        }

        foreach (['siteLogoUpload'] as $attribute) {
            if (!($file = UploadedFile::getInstance($this, $attribute))) {
                continue;
            }

            $newFileName = $file->name;
            $file->saveAs($storagePath . '/' . $newFileName);
            if (!is_file($storagePath . '/' . $newFileName)) {
                $this->addError('logo', 'Cannot move the Logo into the correct storage folder!');
                continue;
            }
            $existing_file = $storagePath . '/' . $newFileName;
            $newFileName = substr(sha1(time()),0,6) . $newFileName;
            copy($existing_file , $storagePath . '/' . $newFileName );
            $attr = str_replace('Upload', '', $attribute);
            $this->$attr = Yii::getAlias('@web/uploads/images/site/' . $newFileName);
        }
    }
}