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

use app\models\Zone;
use app\models\ZoneSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Controls the actions for zones section
 *
 * @Class ZonesController
 * @package app\modules\admin\controllers
 */
class ZonesController extends \app\modules\admin\yii\web\Controller
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
     * Lists all zones
     *
     * @return string
     */
    public function actionIndex()
    {

        $searchModel = new ZoneSearch();
        $dataProvider = $searchModel->search(request()->queryParams);
        $dataProvider->pagination->pageSize = 10;

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a specific zone
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
     * Creates a new zone
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Zone();

        if ($model->load(request()->post()) && $model->save()) {
            notify()->addSuccess(t('app', 'Your action is complete.'));
            return $this->redirect(['view', 'id' => $model->zone_id]);
        } else {
            return $this->render('form', [
                'action' => 'create',
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates a specific zone
     *
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(request()->post()) && $model->save()) {
            notify()->addSuccess(t('app', 'Your action is complete.'));
            return $this->redirect(['view', 'id' => $model->zone_id]);
        } else {
            return $this->render('form', [
                'action' => 'update',
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes a specific zone
     *
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        notify()->addSuccess(t('app', 'Your action is complete.'));

        return $this->redirect(['/admin/zones']);
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

        return $this->redirect(['/admin/zones']);
    }

    public function actionDeactivate($id)
    {
        $model =  $this->findModel($id);
        $model->deactivate();
        notify()->addSuccess(t('app', 'Your action is complete.'));

        return $this->redirect(['/admin/zones']);
    }

    /**
     * @param $id
     * @return static
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Zone::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @return \yii\web\Response
     */
    public function actionActivateAll()
    {
        if (Zone::updateAll(['status' => Zone::STATUS_ACTIVE])) {
            notify()->addSuccess(t('app','All Zones has been activated!'));
        }
        else {
            notify()->addError(t('app','Something went wrong!'));
        }
        return $this->redirect(['/admin/zones']);

    }

    /**
     * @return \yii\web\Response
     */
    public function actionDeactivateAll()
    {
        if (Zone::updateAll(['status' => Zone::STATUS_INACTIVE])) {
            notify()->addWarning(t('app','All Zones has been deactivated!'));
        }
        else {
            notify()->addError(t('app','Something went wrong!'));
        }
        return $this->redirect(['/admin/zones']);
    }
}
