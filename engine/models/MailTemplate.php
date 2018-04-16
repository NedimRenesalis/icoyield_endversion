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

use yii\behaviors\SluggableBehavior;

/**
 * Class MailTemplate
 * @package app\models
 */
class MailTemplate extends \app\models\auto\MailTemplate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['template_type', 'name', 'subject', 'content'], 'required'],
            [['template_type', 'isPlainContent'], 'integer'],
            [['content'], 'string'],
            [['name', 'subject'], 'string', 'max' => 80],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'template_id'       => t('app', 'Template ID'),
            'template_type'     => t('app', 'Template Type'),
            'name'              => t('app', 'Template Name'),
            'slug'              => t('app', 'Slug'),
            'subject'           => t('app', 'Subject'),
            'isPlainContent'    => t('app', 'Is Plain Content'),
            'content'           => t('app', 'Content'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors[] = [
            'class'     => SluggableBehavior::className(),
            'attribute' => 'name',
        ];

        return $behaviors;
    }
}
