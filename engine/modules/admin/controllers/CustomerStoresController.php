<?php

/**
 *
 * @package    EasyAds
 * @author     CodinBit <contact@codinbit.com>
 * @link       https://www.easyads.io
 * @copyright  2017 EasyAds (https://www.easyads.io)
 * @license    https://www.easyads.io
 * @since      1.0.1
 */

namespace app\modules\admin\controllers;

use app\models\CustomerStore;
use app\models\CustomerStoreSearch;
use yii\web\NotFoundHttpException;

/**
 * Controls the actions for stores section
 *
 * @Class CustomerStoresController
 * @package app\modules\admin\controllers
 */
class CustomerStoresController extends \app\modules\admin\yii\web\Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {

        $searchModel = new CustomerStoreSearch();
        $dataProvider = $searchModel->search(request()->queryParams);
        $dataProvider->pagination->pageSize=10;

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
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
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(request()->post()) && $model->save()) {
            notify()->addSuccess(t('app','Your action is complete.'));
            return $this->redirect(['view', 'id' => $model->store_id]);
        } else {
            return $this->render('form', [
                'action'=> 'update',
                'model' => $model,
            ]);
        }
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $model =  $this->findModel($id);
        $model->delete();
        notify()->addSuccess(t('app','Your action is complete.'));

        return $this->redirect(['/admin/customer-stores']);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionActivate($id)
    {
        $model =  $this->findModel($id);
        $model->activate();
        notify()->addSuccess(t('app','Your action is complete.'));

        return $this->redirect(['/admin/customers']);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDeactivate($id)
    {
        $model =  $this->findModel($id);
        $model->deactivate();
        notify()->addSuccess(t('app','Your action is complete.'));

        return $this->redirect(['/admin/customers']);
    }

    /**
     * @param $id
     * @return static
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = CustomerStore::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
