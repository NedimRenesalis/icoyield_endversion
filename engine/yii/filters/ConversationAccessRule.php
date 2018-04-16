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

namespace app\yii\filters;

use app\models\Conversation;
use yii\filters\AccessRule;
use yii\web\NotFoundHttpException;

class ConversationAccessRule extends AccessRule
{
    public $allow = true;
    public $roles = ['@'];

    /**
     * Check if customer is owner of conversation
     *
     * @param \yii\base\Action $action
     * @param \yii\web\User    $user
     * @param \yii\web\Request $request
     * @return bool|null
     */
    public function allows($action, $user, $request)
    {
        return !\Yii::$app->customer->isGuest ? (in_array(\Yii::$app->customer->identity->id, $this->getConversationParticipants($request))) : false;
    }

    /**
     * Retrieve conversation participants
     *
     * @param $request
     * @return array
     */
    protected function getConversationParticipants($request)
    {
        $conversationUid = $request->get('conversation_uid') ? $request->get('conversation_uid') : $request->post('conversation_uid');
        $conversation = $this->findConversation($conversationUid);

        return [$conversation->seller_id, $conversation->buyer_id];
    }

    /**
     * Find conversation
     *
     * @param $id
     * @return Conversation
     * @throws NotFoundHttpException
     */
    protected function findConversation($uid)
    {
        if (($model = Conversation::find()->where(['conversation_uid' => $uid])->one()) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(t('app', 'The requested page does not exist.'));
    }

}