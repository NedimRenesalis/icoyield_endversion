<?php

namespace app\modules\admin\controllers;

use app\models\ConversationMessage;
use app\models\ConversationMessageSearch;
use Yii;
use app\models\Conversation;
use app\models\ConversationSearch;
use app\modules\admin\yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ConversationsController implements the CRUD actions for Conversation model.
 */
class ConversationsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete'                   => ['POST'],
                    'delete-message'           => ['POST'],
                    'delete-multiple'          => ['POST'],
                    'delete-multiple-messages' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Conversation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ConversationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('list', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Conversation model and list of messages of conversation.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel = new ConversationMessageSearch();
        $searchModel->conversation_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'model'        => $this->findModel($id, ['conversationMessages', 'seller', 'buyer', 'listing']),
        ]);
    }

    /**
     * Deletes an existing Conversation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing ConversationMessage model.
     * If deletion is successful, the browser will be redirected to the 'view' page.
     *
     * @param $messageId
     * @param $conversationId
     * @return \yii\web\Response
     */
    public function actionDeleteMessage($messageId, $conversationId)
    {
        $this->findMessageModel($messageId)->delete();

        return $this->redirect(['view', 'id' => $conversationId]);
    }

    public function actionDeleteMultiple()
    {
        $pk = Yii::$app->request->post('pk'); // Array or selected records primary keys

        // Preventing extra unnecessary query
        if (!$pk) {
            return;
        }

        Conversation::deleteAll(['conversation_id' => $pk]);
    }

    public function actionDeleteMultipleMessages()
    {
        $pk = Yii::$app->request->post('pk'); // Array or selected records primary keys

        // Preventing extra unnecessary query
        if (!$pk) {
            return;
        }

        ConversationMessage::deleteAll(['conversation_message_id' => $pk]);
    }

    /**
     * Finds the Conversation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param       $id
     * @param array $relations
     *
     * @return Conversation the loaded model
     * @throws NotFoundHttpException
     */
    protected function findModel($id, array $relations = [])
    {
        $query = Conversation::find()->where(['conversation_id' => $id]);

        if (!empty($relations)) {
            $query->with($relations);
        }

        if (($model = $query->one()) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(t('app', 'The requested page does not exist.'));
    }

    /**
     * Finds the ConversationMessage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return ConversationMessage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findMessageModel($id)
    {
        if (($model = ConversationMessage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(t('app', 'The requested page does not exist.'));
        }
    }
}
