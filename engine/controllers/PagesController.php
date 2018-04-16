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

namespace app\controllers;

use app\models\Page;
use yii\filters\VerbFilter;
use app\yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class PagesController
 * @package app\controllers
 */
class PagesController extends Controller
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
     * Displays static page.
     *
     * @param $slug
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionIndex($slug)
    {
        $page = $this->findPageBySlug($slug);

        // show inactive pages just for admin
        if (app()->user->isGuest && $page->status == Page::STATUS_INACTIVE) {
            throw new NotFoundHttpException(t('app', 'The requested page does not exist.'));
        }

        // Meta tags
        app()->view->registerMetaTag([
            'name'    => 'keywords',
            'content' => $page->keywords ? $page->keywords : 'Default'
        ]);
        app()->view->registerMetaTag([
            'name'    => 'description',
            'content' => $page->description ? $page->description : 'Default'
        ]);

        app()->view->title = $page->title . ' - ' . options()->get('app.settings.common.siteName', 'EasyAds');

        return $this->render('index', ['page' => $page]);
    }

    /**
     * Find static page by unique slug
     *
     * @param $slug
     *
     * @return mixed
     * @throws NotFoundHttpException
     */
    protected function findPageBySlug($slug)
    {
        if (($page = Page::findOne(['slug' => $slug])) !== null) {
            return $page;
        }
        throw new NotFoundHttpException(t('app', 'The requested page does not exist.'));
    }
}