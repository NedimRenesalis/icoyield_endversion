<?php

namespace app\controllers;

use app\components\mail\template\TemplateTypeAdConversation;
use app\models\ConversationMessage;
use app\yii\web\Controller;
use app\models\Conversation;
use yii\filters\AccessControl;
use yii\helpers\Url;
use app\yii\filters\ConversationAccessRule;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

class ConversationController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'ownerAccess' => [
                'class'      => AccessControl::className(),
                'only'       => ['reply', 'archive', 'block-customer', 'delete'],
                'rules'      => [['actions' => ['reply', 'archive', 'block-customer', 'delete']]],
                'ruleConfig' => ['class' => ConversationAccessRule::className()],
                'user'       => 'customer'
            ],
            'verbs'       => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete'  => ['POST'],
                    'archive' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @param $conversation_uid
     * @return string|Response
     */
    public function actionReply($conversation_uid)
    {
        $conversation = $this->findModel($conversation_uid, ['conversationMessages', 'seller', 'buyer', 'listing']);

        // add new message
        $conversationMessage = new ConversationMessage();
        $conversationMessage->conversation_id = $conversation->conversation_id;
        if ($conversation->isCustomerSeller(app()->customer->id)) {
            $conversationMessage->seller_id = app()->customer->id;
        } else {
            $conversationMessage->buyer_id = app()->customer->id;
        }
        $conversationMessage->is_read = ConversationMessage::NO;

        if (request()->isPost) {
            if ($conversationMessage->load(request()->post()) && $conversationMessage->save()) {
                // in case if conversation is archived we change its status to active
                // because of new message was added
                if ($conversation->isArchived()) {
                    $conversation->status = Conversation::CONVERSATION_STATUS_ACTIVE;
                    $conversation->save(false);
                }

                app()->mailSystem->add('you-have-unread-messages', [
                    'listing_id'     => $conversation->listing->listing_id,
                    'recipient_type' => $conversation->isCustomerSeller(app()->customer->id) ? TemplateTypeAdConversation::RECIPIENT_TYPE_BUYER : TemplateTypeAdConversation::RECIPIENT_TYPE_SELLER,
                    'buyer_name'     => $conversation->buyer->fullName,
                    'buyer_email'    => $conversation->buyer->email,
                    'message'        => $conversationMessage->message,
                    'reply_url'      => Url::to(['conversation/reply', 'conversation_uid' => $conversation->conversation_uid], true),
                ]);

                notify()->addSuccess(t('app', 'Message was successfully sent!'));

                return $this->redirect(['reply', 'conversation_uid' => $conversation->conversation_uid]);
            }
        }

        $this->markInboxMessagesAsRead($conversation);

        return $this->render('reply', [
            'conversation'        => $conversation,
            'conversationMessage' => $conversationMessage
        ]);
    }

    /**
     * Action to archive conversation
     *
     * @return \yii\web\Response
     */
    public function actionArchive()
    {
        /* allow only ajax calls */
        if (!request()->isAjax) {
            return $this->redirect(['account/conversations']);
        }
        /* set the output to json */
        response()->format = Response::FORMAT_JSON;

        $conversationUid = request()->post('conversation_uid');
        $conversation = $this->findModel($conversationUid);
        $conversation->status = Conversation::CONVERSATION_STATUS_ARCHIVED;

        if ($conversation->save(false)) {
            return ['result' => 'success', 'msg' => t('app', 'Conversation was successfully archived.')];
        }
    }

    /**
     * Action to block/unblock buyer by seller
     *
     * @return \yii\web\Response
     */
    public function actionBlockUnblockBuyer()
    {
        /* allow only ajax calls */
        if (!request()->isAjax) {
            return $this->redirect(['account/conversations']);
        }
        /* set the output to json */
        response()->format = Response::FORMAT_JSON;

        $conversationUid = request()->post('conversation_uid');
        $conversation = $this->findModel($conversationUid, ['buyer']);
        $response = [];
        if ($conversation->isBuyerBlocked()) {
            $conversation->is_buyer_blocked = Conversation::NO;
            $response['msg'] = t('app', 'Customer was successfully unblocked.');
        } else {
            $conversation->is_buyer_blocked = Conversation::YES;
            $response['msg'] = t('app', 'Customer was successfully blocked.');
        }

        if ($conversation->save(false)) {
            $response['result'] = 'success';

            return $response;
        }
    }

    /**
     * @return array|Response
     */
    public function actionDelete()
    {
        /* allow only ajax calls */
        if (!request()->isAjax) {
            return $this->redirect(['account/conversations']);
        }
        /* set the output to json */
        response()->format = Response::FORMAT_JSON;

        $conversationUid = request()->post('conversation_uid');
        $conversation = $this->findModel($conversationUid);

        if ($conversation->delete()) {
            return ['result' => 'success', 'msg' => t('app', 'Conversation was deleted.'), 'url' => Url::to('/account/conversations')];
        }
    }

    /**
     * Mark messages from interlocutor as read
     *
     * @param Conversation $conversation
     *
     * @return bool
     */
    public function markInboxMessagesAsRead(Conversation $conversation)
    {
        $isSuccess = true;

        foreach ($conversation->conversationMessages as $message) {
            if ($message->isUnread() && !$message->isAuthor(app()->customer->id)) {
                $message->is_read = ConversationMessage::YES;
                $isSuccess = $isSuccess && $message->save();
            }
        }

        return $isSuccess;
    }

    /**
     * @param $uid
     * @param array $relations
     * @return \app\models\auto\Conversation|array|null
     * @throws NotFoundHttpException
     */
    protected function findModel($uid, array $relations = [])
    {
        $query = Conversation::find()->where(['conversation_uid' => $uid]);

        if (!empty($relations)) {
            $query->with($relations);
        }

        if (($model = $query->one()) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(t('app', 'The requested page does not exist.'));
    }
}
