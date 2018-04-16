<?php

namespace app\models\auto;

/**
 * This is the ActiveQuery class for [[Tax]].
 *
 * @see Tax
 */
class TaxQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Tax[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Tax|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
