<?php

namespace app\models\auto;

/**
 * This is the ActiveQuery class for [[CategoryField]].
 *
 * @see CategoryField
 */
class CategoryFieldQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CategoryField[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CategoryField|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
