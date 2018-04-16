<?php

/**
 *
 * @package    EasyAds
 * @author     CodinBit <contact@codinbit.com>
 * @link       https://www.easyads.io
 * @copyright  2017 EasyAds (https://www.easyads.io)
 * @license    https://www.easyads.io
 * @since      1.0
 */

namespace app\models;

/**
 * This is the ActiveQuery class for [[ConversationMessage]].
 *
 * @see ConversationMessage
 */
class ConversationMessageQuery extends \app\models\auto\ConversationMessageQuery
{
    /**
     * Filter messages that's read
     *
     * @inheritdoc
     * @return ConversationMessage[]|array
     */
    public function read()
    {
        return $this->andWhere(['is_read' => ConversationMessage::YES]);
    }

    /**
     * Filter messages that isn't read
     *
     * @inheritdoc
     * @return ConversationMessage[]|array
     */
    public function notRead()
    {
        return $this->andWhere(['is_read' => ConversationMessage::NO]);
    }
}
