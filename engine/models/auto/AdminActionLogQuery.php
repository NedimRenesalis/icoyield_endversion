<?php

namespace app\models\auto;

/**
 * This is the ActiveQuery class for [[AdminActionLog]].
 *
 * @see AdminActionLog
 */
class AdminActionLogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return AdminActionLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AdminActionLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
