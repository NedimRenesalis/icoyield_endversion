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

namespace app\controllers;

use app\models\CustomerStore;
use app\models\Listing;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use app\yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class StoreController
 * @package app\controllers
 */
class StoreController extends Controller
{

    const ADS_PER_PAGE = 20;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @param $slug
     * @return string
     */
    public function actionIndex($slug)
    {
        $store = $this->findStoreBySlug($slug);


        $storeAds = new ActiveDataProvider([
            'query'      => Listing::find()->where(['customer_id' => $store->customer_id])
                ->andWhere(['status' => Listing::STATUS_ACTIVE])
                ->orderBy(['promo_expire_at' => SORT_DESC, 'created_at' => SORT_DESC])
            ,
            'sort'       => ['defaultOrder' => ['promo_expire_at' => SORT_DESC]],
            'pagination' => [
                'defaultPageSize' => self::ADS_PER_PAGE,
            ],
        ]);

        app()->view->title = $store->store_name . ' - ' . options()->get('app.settings.common.siteName', 'EasyAds');

        return $this->render('index', [
            'store'                   => $store,
            'storeAds'                => $storeAds,
            'isNothingFound'          => !$storeAds->getCount(),
        ]);
    }

    /**
     * Find store by unique slug
     *
     * @param $slug
     *
     * @return mixed
     * @throws NotFoundHttpException
     */
    protected function findStoreBySlug($slug)
    {
        if (($store = CustomerStore::findOne(['slug' => $slug, 'status' => CustomerStore::STATUS_ACTIVE])) !== null) {
            return $store;
        }
        throw new NotFoundHttpException(t('app', 'The requested page does not exist.'));
    }
}