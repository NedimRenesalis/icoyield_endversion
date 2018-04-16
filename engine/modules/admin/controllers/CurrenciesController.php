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

use app\models\Currency;
use app\models\CurrencySearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Controls the actions for currencies section
 *
 * @Class CurrenciesController
 * @package app\modules\admin\controllers
 */
class CurrenciesController extends \app\modules\admin\yii\web\Controller
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
     * Lists all currencies
     *
     * @return string
     */
    public function actionIndex()
    {

        $searchModel = new CurrencySearch();
        $dataProvider = $searchModel->search(request()->queryParams);
        $dataProvider->pagination->pageSize=10;

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a specific currency
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
     * Creates a new currency
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Currency();

        if ($model->load(request()->post()) && $model->save()) {
            notify()->addSuccess(t('app','Your action is complete.'));
            return $this->redirect(['view', 'id' => $model->currency_id]);
        } else {
            return $this->render('form', [
                'action'=> 'create',
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates a specific currency
     *
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(request()->post()) && $model->save()) {
            notify()->addSuccess(t('app','Your action is complete.'));
            return $this->redirect(['view', 'id' => $model->currency_id]);
        } else {
            return $this->render('form', [
                'action'=> 'update',
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes a specific currency
     *
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        notify()->addSuccess(t('app','Your action is complete.'));

        return $this->redirect(['/admin/currencies']);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionActivate($id)
    {
        $model =  $this->findModel($id);
        $model->activate();
        notify()->addSuccess(t('app', 'Your action is complete.'));

        return $this->redirect(['/admin/currencies']);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDeactivate($id)
    {
        $model =  $this->findModel($id);
        $model->deactivate();
        notify()->addSuccess(t('app', 'Your action is complete.'));

        return $this->redirect(['/admin/currencies']);
    }

    /**
     * Finds the Currency model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Currency the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Currency::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @return \yii\web\Response
     */
    public function actionActivateAll()
    {
        if (Currency::updateAll(['status' => Currency::STATUS_ACTIVE])) {
            notify()->addSuccess(t('app','All Currencies has been activated!'));
        }
        else {
            notify()->addError(t('app','Something went wrong!'));
        }
        return $this->redirect(['/admin/currencies']);

    }

    /**
     * @return \yii\web\Response
     */
    public function actionDeactivateAll()
    {
        if (Currency::updateAll(['status' => Currency::STATUS_INACTIVE])) {
            notify()->addWarning(t('app','All Currencies has been deactivated!'));
        }
        else {
            notify()->addError(t('app','Something went wrong!'));
        }
        return $this->redirect(['/admin/currencies']);
    }
}
