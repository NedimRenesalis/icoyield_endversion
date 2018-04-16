<?php

namespace app\models\auto;

/**
 * This is the ActiveQuery class for [[OrderTransaction]].
 *
 * @see OrderTransaction
 */
class OrderTransactionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return OrderTransaction[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return OrderTransaction|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
