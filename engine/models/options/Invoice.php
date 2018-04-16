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
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * Class Invoice
 * @package app\models\options
 */
class Invoice extends Base
{
    /**
     * @var string
     */
    public $prefix = '';

    /**
     * @var string
     */
    public $logo;

    /**
     * @var
     */
    public $logoUpload;

    /**
     * @var string
     */
    public $notes = '';

    /**
     *$var int
     */
    public $disableInvoices = 0;

    /**
     * @var string
     */
    protected $categoryName = 'app.settings.invoice';


    public function rules()
    {
        return [
            [['prefix'], 'required'],
            [['prefix'], 'string', 'max' => 6],
            [['logoUpload'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['logoUpload', 'notes','disableInvoices'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'prefix'            => t('app', 'Prefix'),
            'logoUpload'        => t('app', 'Invoice Logo'),
            'notes'             => t('app', 'Notes'),
            'disableInvoices'   => t('app', 'Hide Invoice'),
        ];
    }

    /**
     * @return array
     */
    public function attributeHelpTexts()
    {
        return [
            'prefix'            => t('app', 'Prefix which goes before identifier of invoice'),
            'logoUpload'        => t('app', 'Logotype for invoice'),
            'notes'             => t('app', 'Notes that you would like to provide in invoice'),
            'disableInvoices'    => t('app', 'If it\'s set to yes then invoice section will be hidden'),
        ];
    }

    /**
     * @return mixed
     */
    public function attributePlaceholders()
    {
        return ArrayHelper::merge($this->attributeLabels(), [
            'prefix' => 'INV_',
        ]);
    }

    /**
     * After validate handler
     */
    public function afterValidate()
    {
        parent::afterValidate();
        $this->handleUploadedFiles();
    }

    /**
     * Upload files handler
     */
    protected function handleUploadedFiles()
    {

        if ($this->hasErrors()) {
            return;
        }

        $storagePath = Yii::getAlias('@webroot/uploads/images/site');

        if (!file_exists($storagePath) || !is_dir($storagePath)) {
            if (!@mkdir($storagePath, 0777, true)) {
                $this->addError('logoUpload', 'The images storage directory({path}) does not exists and cannot be created!' . $storagePath);

                return;
            }
        }

        if (!($file = UploadedFile::getInstance($this, 'logoUpload'))) {
            return;
        }

        $newFileName = $file->name;
        $file->saveAs($storagePath . '/' . $newFileName);
        if (!is_file($storagePath . '/' . $newFileName)) {
            $this->addError('logoUpload', 'Cannot move the Logo into the correct storage folder!');

            return;
        }
        $existing_file = $storagePath . '/' . $newFileName;
        $newFileName = substr(sha1(time()), 0, 6) . $newFileName;
        copy($existing_file, $storagePath . '/' . $newFileName);
        $attr = str_replace('Upload', '', 'logoUpload');
        $this->$attr = Yii::getAlias('@web/uploads/images/site/' . $newFileName);
    }
}