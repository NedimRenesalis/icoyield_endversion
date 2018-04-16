<?php

namespace app\models\auto;

use Yii;

/**
 * This is the model class for table "{{%group_route_access}}".
 *
 * @property integer $route_id
 * @property integer $group_id
 * @property string $route
 * @property string $access
 * @property string $created_at
 *
 * @property Group $group
 */
class GroupRouteAccess extends \app\yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%group_route_access}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'created_at'], 'required'],
            [['group_id'], 'integer'],
            [['created_at'], 'safe'],
            [['route'], 'string', 'max' => 100],
            [['access'], 'string', 'max' => 5],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::className(), 'targetAttribute' => ['group_id' => 'group_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'route_id' => 'Route ID',
            'group_id' => 'Group ID',
            'route' => 'Route',
            'access' => 'Access',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Group::className(), ['group_id' => 'group_id']);
    }

    /**
     * @inheritdoc
     * @return GroupRouteAccessQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GroupRouteAccessQuery(get_called_class());
    }
}
