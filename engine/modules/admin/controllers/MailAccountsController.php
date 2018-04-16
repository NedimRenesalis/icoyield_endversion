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

namespace app\modules\admin\controllers;

use app\models\MailAccount;
use app\models\MailAccountSearch;
use app\modules\admin\yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Controls the actions for mail accounts section
 *
 * @Class MailAccountsController
 * @package app\modules\admin\controllers
 */
class MailAccountsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all email accounts
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MailAccountSearch();
        $dataProvider = $searchModel->search(request()->queryParams);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a specific account
     *
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new account
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new MailAccount();

        if ($model->load(request()->post()) && $model->save()) {
            notify()->addSuccess(t('app','Your action is complete.'));
            return $this->redirect(['view', 'id' => $model->account_id]);
        } else {
            return $this->render('form', [
                'action' => 'create',
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates a specific account
     *
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(request()->post()) && $model->save()) {
            notify()->addSuccess(t('app','Your action is complete.'));
            return $this->redirect(['view', 'id' => $model->account_id]);
        } else {
            //for multiple select
            $model->used_for = explode(',', $model->used_for);
            return $this->render('form', [
                'action' => 'update',
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes a specific account
     *
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        notify()->addSuccess(t('app','Your action is complete.'));

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return static
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = MailAccount::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
