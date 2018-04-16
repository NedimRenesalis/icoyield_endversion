<?php

namespace app\models\auto;

use Yii;

/**
 * This is the model class for table "{{%admin_action_log}}".
 *
 * @property integer $action_id
 * @property string $controller_name
 * @property string $action_name
 * @property string $changed_model
 * @property string $changed_data
 * @property string $element
 * @property integer $changed_by
 * @property string $created_at
 *
 * @property User $changedBy
 */
class AdminActionLog extends \app\yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_action_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['controller_name', 'action_name', 'changed_model', 'changed_data', 'element', 'created_at'], 'required'],
            [['changed_data'], 'string'],
            [['changed_by'], 'integer'],
            [['created_at'], 'safe'],
            [['controller_name', 'action_name', 'changed_model', 'element'], 'string', 'max' => 255],
            [['changed_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['changed_by' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'action_id' => 'Action ID',
            'controller_name' => 'Controller Name',
            'action_name' => 'Action Name',
            'changed_model' => 'Changed Model',
            'changed_data' => 'Changed Data',
            'element' => 'Element',
            'changed_by' => 'Changed By',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChangedBy()
    {
        return $this->hasOne(User::className(), ['user_id' => 'changed_by']);
    }

    /**
     * @inheritdoc
     * @return AdminActionLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AdminActionLogQuery(get_called_class());
    }
}
