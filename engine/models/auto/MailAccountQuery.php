<?php

namespace app\models\auto;

/**
 * This is the ActiveQuery class for [[MailAccount]].
 *
 * @see MailAccount
 */
class MailAccountQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return MailAccount[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MailAccount|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
