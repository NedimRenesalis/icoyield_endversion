<?php

namespace app\models\auto;

/**
 * This is the ActiveQuery class for [[CustomerStore]].
 *
 * @see CustomerStore
 */
class CustomerStoreQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CustomerStore[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CustomerStore|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
