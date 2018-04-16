<?php

namespace app\models\auto;

/**
 * This is the ActiveQuery class for [[CategoryFieldValue]].
 *
 * @see CategoryFieldValue
 */
class CategoryFieldValueQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CategoryFieldValue[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CategoryFieldValue|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
