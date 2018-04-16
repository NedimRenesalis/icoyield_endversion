<?php

namespace app\models\auto;

/**
 * This is the ActiveQuery class for [[ListingImage]].
 *
 * @see ListingImage
 */
class ListingImageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ListingImage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ListingImage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
