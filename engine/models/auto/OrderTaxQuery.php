<?php

namespace app\models\auto;

/**
 * This is the ActiveQuery class for [[OrderTax]].
 *
 * @see OrderTax
 */
class OrderTaxQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return OrderTax[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return OrderTax|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
