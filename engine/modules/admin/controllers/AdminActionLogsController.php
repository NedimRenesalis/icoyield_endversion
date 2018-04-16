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

use app\models\AdminActionLogSearch;
use app\modules\admin\yii\web\Controller;
use app\models\AdminActionLog;
use yii\web\NotFoundHttpException;

/**
 * Controls the actions for admin action logs section
 *
 * @Class AdminActionLogsController
 * @package app\modules\admin\controllers
 */
class AdminActionLogsController extends Controller
{
    /**
     * Lists all Admin logs
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdminActionLogSearch();
        $dataProvider = $searchModel->search(request()->queryParams);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     *  View a specific Admin log
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
     * Deletes all logs
     *
     * @return int
     */
    public function actionClear()
    {
        AdminActionLog::deleteAll();

        notify()->addSuccess(t('app','Your action is complete'));

        return $this->redirect(['index']);
    }

    /**
     * Finds the AdminActionLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminActionLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminActionLog::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
