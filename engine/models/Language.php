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

use app\helpers\FileSystemHelper;

/**
 * Class Language
 * @package app\models
 */
class Language extends \app\models\auto\Language
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'language_code'], 'required'],
            [['status','is_default'], 'string'],
            [['name', 'language_code', 'region_code'], 'string', 'max' => 100],
            [['language_code', 'region_code'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name'          => t('app', 'Language Name'),
            'language_code' => t('app', 'Language Code'),
            'region_code'   => t('app', 'Region Code'),
            'is_default'    => t('app', 'Is Default'),
            'status'        => t('app', 'Status'),
            'created_at'    => t('app', 'Created At'),
            'updated_at'    => t('app', 'Updated At'),
        ];
    }

    /**
     * @return bool
     */
    public function activate()
    {
        if ($this->status == self::STATUS_INACTIVE){
            $this->status = self::STATUS_ACTIVE;
            $this->save(false);
        }
        return true;
    }

    public function deactivate()
    {
        if ($this->status == self::STATUS_ACTIVE){
            $this->status = self::STATUS_INACTIVE;
            $this->save(false);
        }
        return true;
    }

    /**
     * afterSave
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->handleAddingLanguageTranslationFolder();
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * handleAddingLanguageTranslationFolder
     */
    public function handleAddingLanguageTranslationFolder()
    {
        if ($this->hasErrors()) {
            return;
        }

        $translationFolder = (!empty($this->region_code)) ? $this->language_code . '-' . $this->region_code : $this->language_code;

        $defaultLanguageFolder = \Yii::getAlias('@app/messages/en');

        $storagePath = \Yii::getAlias('@app/messages/'.$translationFolder);

        if (!file_exists($storagePath) || !is_dir($storagePath)) {
            if (!FileSystemHelper::copyFilesRecursive($defaultLanguageFolder, $storagePath)) {
                notify()->addError(t('app', 'System was not able to create translation folder for this language, please create it manually'));
                return;
            }
        }

        notify()->addSuccess(t('app', 'Your translation folder is in: {transFile}',['transFile' => $storagePath]));
    }

    /**
     * afterDelete
     */
    public function afterDelete()
    {
        parent::afterDelete();
        $this->handleDeleteLanguageTranslationFolder();
    }

    /**
     * handleDeleteLanguageTranslationFolder
     */
    public function handleDeleteLanguageTranslationFolder()
    {
        if ($this->hasErrors()) {
            return;
        }

        $translationFolder = (!empty($this->region_code)) ? $this->language_code . '-' . $this->region_code : $this->language_code;
        $storagePath = \Yii::getAlias('@webroot/engine/messages/'.$translationFolder);

        if (file_exists($storagePath) || is_dir($storagePath)) {
            array_map('unlink', glob("$storagePath/*.*"));
            rmdir($storagePath);
        }

        notify()->addSuccess(t('app', 'Your translation folder is removed from : {transFile}',['transFile' => $storagePath]));
    }

}
