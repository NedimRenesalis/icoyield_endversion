<?php

namespace app\models\auto;

/**
 * This is the ActiveQuery class for [[CategoryFieldType]].
 *
 * @see CategoryFieldType
 */
class CategoryFieldTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CategoryFieldType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CategoryFieldType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
