<?php

namespace app\models\auto;

/**
 * This is the ActiveQuery class for [[ListingFavorite]].
 *
 * @see ListingFavorite
 */
class ListingFavoriteQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ListingFavorite[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ListingFavorite|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
