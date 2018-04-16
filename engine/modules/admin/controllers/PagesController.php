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

use app\models\Page;
use app\models\PageSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * Controls the actions for pages section
 *
 * @Class PagesController
 * @package app\modules\admin\controllers
 */
class PagesController extends \app\modules\admin\yii\web\Controller
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
     * Lists all the pages
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PageSearch();
        $dataProvider = $searchModel->search(request()->queryParams);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a specific page
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
     * Creates a new page
     *
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Page();

        if ($model->load(request()->post()) && $model->save()) {
            $sectionPageCount = Page::find()
                ->where(['section' => $model->section, 'status' => Page::STATUS_ACTIVE])
                ->count();

            // number of pages in section is hardcoded,
            // because of that value could be changed from widget
            if ($sectionPageCount > 10) {
                notify()->addWarning(t('app', 'Some of the pages may not be displayed due to the exceeded number of pages in footer {section} section.', ['section' => Page::getSectionsList($model->section)]));
            }else {
                notify()->addSuccess(t('app','Your action is complete.'));
            }

            return $this->redirect(['view', 'id' => $model->page_id]);
        } else {
            return $this->render('form', [
                'action' => 'create',
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates a specific page
     *
     * @param $id
     * @return string|Response
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(request()->post()) && $model->save()) {
            $sectionPageCount = Page::find()
                ->where(['section' => $model->section, 'status' => Page::STATUS_ACTIVE])
                ->count();

            // number of pages in section is hardcoded,
            // because of that value could be changed from widget
            if ($sectionPageCount > 10) {
                notify()->addWarning(t('app', 'Some of the pages may not be displayed due to the exceeded number of pages in footer {section} section.', ['section' => Page::getSectionsList($model->section)]));
            } else {
                notify()->addSuccess(t('app','Your action is complete.'));
            }

            return $this->redirect(['view', 'id' => $model->page_id]);
        } else {
            return $this->render('form', [
                'action' => 'update',
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes a specific page
     *
     * @param $id
     * @return Response
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        notify()->addSuccess(t('app','Your action is complete.'));

        return $this->redirect(['index']);
    }

    /**
     * Gets Positions for pages
     *
     * @return array|Response
     */
    public function actionGetAvailablePositions()
    {
        /* allow only ajax calls */
        if (!request()->isAjax) {
            return $this->redirect(['pages/create']);
        }

        /* set the output to json */
        response()->format = Response::FORMAT_JSON;

        $sectionId = request()->post('section_id');
        $pageId = request()->post('page_id');
        if (empty($sectionId)) {
            return ['result' => 'error', 'html' => t('app', 'Something went wrong...')];
        }

        if ($pageId) {
            $page = $this->findModel($pageId);
        } else {
            $page = new Page();
        }

        $availablePositions = $page->getListOfAvailablePositions($sectionId);

        return ['result' => 'success', 'response' => $availablePositions];
    }

    /**
     * @param $id
     * @return static
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
