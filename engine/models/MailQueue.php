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

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * Class MailQueue
 * @package app\models
 */
class MailQueue extends \app\models\auto\MailQueue
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors[] = [
            'class'              => TimestampBehavior::className(),
            'value'              => new Expression('NOW()'),
            'updatedAtAttribute' => 'last_attempt_time',
        ];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'attempts', 'last_attempt_time', 'sent_time', 'message_template_type'], 'integer'],
            [['time_to_send', 'swift_message', 'message_template_type'], 'required'],
            [['subject'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                    => t('app', 'ID'),
            'subject'               => t('app', 'Subject'),
            'message_template_type' => t('app', 'Template Type'),
            'attempts'              => t('app', 'Attempts'),
            'last_attempt_time'     => t('app', 'Last Attempt time'),
            'time_to_send'          => t('app', 'Time To Send'),
            'created_at'            => t('app', 'Created At'),
        ];
    }

    /**
     * @return mixed
     */
    public function toMessage()
    {
        return unserialize(base64_decode($this->swift_message));
    }
}
