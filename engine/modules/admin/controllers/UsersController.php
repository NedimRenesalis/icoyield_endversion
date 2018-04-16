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

use app\models\User;
use app\models\UserSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Controls the actions for users section
 *
 * @Class UsersController
 * @package app\modules\admin\controllers
 */
class UsersController extends \app\modules\admin\yii\web\Controller
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
     * Lists all users
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(request()->queryParams);
        $dataProvider->pagination->pageSize=10;

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a specific user
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
     * Creates a new user
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new User([
            'scenario' => User::SCENARIO_CREATE
        ]);

        if ($model->load(request()->post()) && $model->save()) {
            $model->sendRegistrationEmail();
            notify()->addSuccess(t('app','Your action is complete.'));
            return $this->redirect(['view', 'id' => $model->user_id]);
        } else {
            return $this->render('form', [
                'action'=> 'create',
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates a specific user
     *
     * @param $id
     * @return string|\yii\web\Response
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $user = request()->post('User');

        if ($model->load(request()->post())) {
            if ($model->user_id == 1 && $user['group_id'] != 1)
            {
                throw new \yii\web\ForbiddenHttpException(t('app','This action is not allowed!'));
            }
        }
        if ($model->load(request()->post()) && $model->save()) {
            notify()->addSuccess(t('app','Your action is complete.'));
            return $this->redirect(['view', 'id' => $model->user_id]);
        } else {
            return $this->render('form', [
                'action'=> 'update',
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes a specific user
     *
     * @param $id
     * @return void|\yii\web\Response
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($id == 1) {
            notify()->addError(t('app','This action is not allowed!'));
            return $this->redirect(['/admin/users']);
        }

        $model->deactivate();

        notify()->addSuccess(t('app','Your action is complete.'));

        return $this->redirect(['/admin/users']);
    }

    /**
     * @param $id
     * @return static
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
