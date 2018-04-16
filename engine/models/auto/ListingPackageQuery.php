<?php

namespace app\models\auto;

/**
 * This is the ActiveQuery class for [[ListingPackage]].
 *
 * @see ListingPackage
 */
class ListingPackageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ListingPackage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ListingPackage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
