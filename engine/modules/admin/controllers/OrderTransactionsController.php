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

use app\models\OrderTransaction;
use app\models\OrderTransactionSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Controls the actions for orders transactions section
 *
 * @Class OrderTransactionsController
 * @package app\modules\admin\controllers
 */
class OrderTransactionsController extends \app\modules\admin\yii\web\Controller
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
     * Lists all the transactions
     *
     * @return string
     */
    public function actionIndex()
    {

        $searchModel = new OrderTransactionSearch();
        $dataProvider = $searchModel->search(request()->queryParams);
        $dataProvider->pagination->pageSize=10;
        $dataProvider->setSort([
            'defaultOrder' => ['transaction_id'=>SORT_DESC]
        ]);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a specific transaction
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
     * @param $id
     * @return static
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = OrderTransaction::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
