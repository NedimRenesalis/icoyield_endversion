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

use app\models\Customer;
use app\models\CustomerSearch;
use yii\web\NotFoundHttpException;

/**
 * Controls the actions for customers section
 *
 * @Class CustomersController
 * @package app\modules\admin\controllers
 */
class CustomersController extends \app\modules\admin\yii\web\Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {

        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(request()->queryParams);
        $dataProvider->pagination->pageSize=10;

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all customers
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
     * Creates a new customer
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Customer([
            'scenario' => Customer::SCENARIO_ADMIN_CREATE
        ]);

        if ($model->load(request()->post()) && $model->save()) {
            $model->sendRegistrationEmail();
            notify()->addSuccess(t('app','Your action is complete.'));
            return $this->redirect(['view', 'id' => $model->customer_id]);
        } else {
            return $this->render('form', [
                'action'=> 'create',
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates a specific customer
     *
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(request()->post()) && $model->save()) {
            notify()->addSuccess(t('app','Your action is complete.'));
            return $this->redirect(['view', 'id' => $model->customer_id]);
        } else {
            return $this->render('form', [
                'action'=> 'update',
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes a specific customer
     *
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $model =  $this->findModel($id);
        $model->delete();
        notify()->addSuccess(t('app','Your action is complete.'));

        return $this->redirect(['/admin/customers']);
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
     * Impersonate a specific customer
     *
     * @param $id
     * @return \yii\web\Response
     */
    public function actionImpersonate($id)
    {
        $customer = Customer::findOne(['customer_id' => (int)$id, 'status' => Customer::STATUS_ACTIVE]);
        if (empty($customer)) {
            notify()->addWarning(t('app', 'Please specify a valid customer'));
            return $this->redirect(['/admin/customers']);
        }

        app()->customer->switchIdentity($customer);

        session()->set('impersonating_customer', true);
        return $this->redirect(['/account']);
    }

    /**
     * @param $id
     * @return static
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Customer::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
