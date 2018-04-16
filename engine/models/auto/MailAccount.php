<?php

namespace app\models\auto;

use Yii;

/**
 * This is the model class for table "{{%mail_account}}".
 *
 * @property integer $account_id
 * @property string $account_name
 * @property string $hostname
 * @property string $username
 * @property string $password
 * @property integer $port
 * @property string $encryption
 * @property integer $timeout
 * @property string $from
 * @property string $reply_to
 * @property string $used_for
 * @property string $created_at
 * @property string $updated_at
 */
class MailAccount extends \app\yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mail_account}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_name', 'hostname', 'username', 'password', 'port', 'from', 'used_for', 'created_at', 'updated_at'], 'required'],
            [['port', 'timeout'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['account_name', 'hostname', 'username', 'password', 'encryption', 'from', 'reply_to', 'used_for'], 'string', 'max' => 255],
            [['username'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'account_id' => 'Account ID',
            'account_name' => 'Account Name',
            'hostname' => 'Hostname',
            'username' => 'Username',
            'password' => 'Password',
            'port' => 'Port',
            'encryption' => 'Encryption',
            'timeout' => 'Timeout',
            'from' => 'From',
            'reply_to' => 'Reply To',
            'used_for' => 'Used For',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @inheritdoc
     * @return MailAccountQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MailAccountQuery(get_called_class());
    }
}
