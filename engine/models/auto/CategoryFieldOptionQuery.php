<?php

namespace app\models\auto;

/**
 * This is the ActiveQuery class for [[CategoryFieldOption]].
 *
 * @see CategoryFieldOption
 */
class CategoryFieldOptionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CategoryFieldOption[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CategoryFieldOption|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
