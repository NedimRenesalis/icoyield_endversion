<?php

namespace app\models\auto;

/**
 * This is the ActiveQuery class for [[Conversation]].
 *
 * @see Conversation
 */
class ConversationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Conversation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Conversation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
