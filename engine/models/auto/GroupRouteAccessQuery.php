<?php

namespace app\models\auto;

/**
 * This is the ActiveQuery class for [[GroupRouteAccess]].
 *
 * @see GroupRouteAccess
 */
class GroupRouteAccessQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return GroupRouteAccess[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return GroupRouteAccess|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
