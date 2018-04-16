<?php

namespace app\models\auto;

/**
 * This is the ActiveQuery class for [[ListingStat]].
 *
 * @see ListingStat
 */
class ListingStatQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ListingStat[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ListingStat|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
