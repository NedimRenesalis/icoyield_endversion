<?php

namespace app\models\auto;

use Yii;

/**
 * This is the model class for table "{{%mail_queue}}".
 *
 * @property integer $id
 * @property string $subject
 * @property string $swift_message
 * @property integer $attempts
 * @property integer $message_template_type
 * @property string $last_attempt_time
 * @property string $time_to_send
 * @property string $sent_time
 * @property string $created_at
 */
class MailQueue extends \app\yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mail_queue}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['swift_message', 'message_template_type', 'created_at'], 'required'],
            [['swift_message'], 'string'],
            [['attempts', 'message_template_type'], 'integer'],
            [['last_attempt_time', 'time_to_send', 'sent_time', 'created_at'], 'safe'],
            [['subject'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subject' => 'Subject',
            'swift_message' => 'Swift Message',
            'attempts' => 'Attempts',
            'message_template_type' => 'Message Template Type',
            'last_attempt_time' => 'Last Attempt Time',
            'time_to_send' => 'Time To Send',
            'sent_time' => 'Sent Time',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @inheritdoc
     * @return MailQueueQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MailQueueQuery(get_called_class());
    }
}
