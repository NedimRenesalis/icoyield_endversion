<?php

namespace app\models\auto;

/**
 * This is the ActiveQuery class for [[ConversationMessage]].
 *
 * @see ConversationMessage
 */
class ConversationMessageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ConversationMessage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ConversationMessage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
