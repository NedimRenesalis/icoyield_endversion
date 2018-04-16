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

use app\fieldbuilder\Type;
use app\models\Listing;
use app\models\ListingSearch;
use app\models\Category;
use app\models\CategoryField;
use app\models\Country;
use app\models\Location;
use app\models\Zone;
use twisted1919\helpers\Icon;
use yii\filters\VerbFilter;
use app\yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\db\Expression;
use yii\web\Response;

/**
 * Class CategoryController
 * @package app\controllers
 */
class CategoryController extends Controller
{
    /**
     * Constants
     */
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
     * @throws NotFoundHttpException
     */
    public function actionIndex($slug)
    {
        $category = $this->findCategoryBySlug($slug);
        $searchCategories = Category::find()->where(['status' => Category::STATUS_ACTIVE])->orderBy(['sort_order' => SORT_ASC])->all();
        $categories = Category::getHierarchyOfCategoriesBySlug($slug, true);
        $childCategories = Category::find()->where(['parent_id' => $category->category_id, 'status' => Category::STATUS_ACTIVE])->orderBy(['sort_order' => SORT_ASC])->all();
        $categoryPlaceholderText = t('app', 'Choose Category');

        if ($slug = request()->get('slug')) {
            $chosenCategory = self::findCategoryBySlug($slug);
            $categoryPlaceholderText = Icon::make($chosenCategory->icon) . ' ' . html_encode($chosenCategory->name);
        }

        $customFields = '';
        // more than one means that is parent category
        if (count($categories) == 1) {
            $categoryId = $category->category_id;
            $categoryFields = CategoryField::find()->where(['category_id' => $categoryId])->orderBy(['sort_order' => SORT_ASC])->all();
            // retrieve custom fields
            $typeData = [];
            foreach ($categoryFields as $field) {
                $typeData[] = [
                    'type'  => $field->type,
                    'field' => $field,
                ];
            }
            foreach ($typeData as $data) {
                $type = $data['type'];
                $field = $data['field'];
                if (!is_file(\Yii::getAlias('@' . str_replace('\\', '/', $type->class_name) . '.php'))) {
                    continue;
                }
                $className = $type->class_name;
                $component = new $className();
                if (!($component instanceof Type)) {
                    continue;
                }
                $component->field = $field;
                $component->params = [
                    'categoryId' => $categoryId,
                ];
                $component->handleFrontendSearchFormDisplay();
            }
            $customFields = $this->renderPartial('_custom-search');
        }

        $searchModel = new ListingSearch();

        // ads
        $AdsProvider = $searchModel->categorySearch(request()->queryParams);
        $AdsProvider->query->andWhere(['category_id' => $categories])
            ->orderBy(['promo_expire_at' => SORT_DESC, 'created_at' => SORT_DESC]);
        $AdsProvider->sort = ['defaultOrder' => ['promo_expire_at' => SORT_DESC]];
        $AdsProvider->pagination = [
            'defaultPageSize' => self::ADS_PER_PAGE,
        ];

        // select location details if filter is not empty
        $locationDetails = '';
        if (isset(request()->queryParams['ListingSearch']) && !empty(request()->queryParams['ListingSearch']['location'])) {
            if(strpos(request()->queryParams['ListingSearch']['location'], 'zo-') === 0){
                $zone = Zone::find()->with('country')->where(['zone_id' => substr(request()->queryParams['ListingSearch']['location'],3)])->one();
                if ($zone) {
                    $locationDetails = $zone->name . ', ' . $zone->country->name;
                }
            } else if(strpos(request()->queryParams['ListingSearch']['location'], 'co-') === 0){
                $country = Country::find()->where(['country_id' => substr(request()->queryParams['ListingSearch']['location'],3)])->one();
                if ($country) {
                    $locationDetails = $country->name;
                }
            } else if(strpos(request()->queryParams['ListingSearch']['location'], 'ci-') === 0){
                $location = Location::find()->where(['LIKE', 'city', substr(request()->queryParams['ListingSearch']['location'],3)])->one();
                if ($location) {
                    $locationDetails = $location->city . ', ' . $location->country->name;
                } else {
                    $locationDetails = html_encode(substr(request()->queryParams['ListingSearch']['location'],3));
                }
            }
        }

        // set custom fields to display if they have value
        $advanceSearchOptions= [];
        if (isset(request()->queryParams['CategoryField'])) {
            $advanceSearchOptions['collapse'] = ' in';
            $advanceSearchOptions['ariaExpanded'] = 'true';
        } else {
            $advanceSearchOptions['collapse'] = '';
            $advanceSearchOptions['ariaExpanded'] = 'false';
        }

        // set the redirect url for child category with value in the search fields
        $paramsSearch = [];
        if (isset(request()->queryParams['ListingSearch'])) {
            $paramsSearch['ListingSearch'] = request()->queryParams['ListingSearch'];
            $paramsSearch['key'] = 'ListingSearch';
        } else {
            $paramsSearch['ListingSearch'] = null;
            $paramsSearch['key'] = null;
        }

        app()->view->title = $category->name . ' - ' . options()->get('app.settings.common.siteName', 'EasyAds');

        return $this->render('index', [
            'params'                  => request()->queryParams,
            'searchModel'             => $searchModel,
            'category'                => $category,
            'categories'              => $searchCategories,
            'categoryPlaceholderText' => $categoryPlaceholderText,
            'adsProvider'             => $AdsProvider,
            'customFields'            => $customFields,
            'locationDetails'         => $locationDetails,
            'isNothingFound'          => !$AdsProvider->getCount(),
            'childCategories'         => $childCategories,
            'advanceSearchOptions'    => $advanceSearchOptions,
            'paramsSearch'            => $paramsSearch,
        ]);
    }

    public function actionMapView($slug)
    {
        $params = request()->queryParams;
        if(!$params['ListingSearch']['location']) {
            $this->redirect(url(['/category/'. $slug]  ));
        };
        $category = $this->findCategoryBySlug($slug);
        $searchCategories = Category::find()->where(['status' => Category::STATUS_ACTIVE])->orderBy(['sort_order' => SORT_ASC])->all();
        $categories = Category::getHierarchyOfCategoriesBySlug($slug, true);
        $childCategories = Category::find()->where(['parent_id' => $category->category_id, 'status' => Category::STATUS_ACTIVE])->orderBy(['sort_order' => SORT_ASC])->all();
        $categoryPlaceholderText = t('app', 'Choose Category');

        if ($slug = request()->get('slug')) {
            $chosenCategory = self::findCategoryBySlug($slug);
            $categoryPlaceholderText = Icon::make($chosenCategory->icon) . ' ' . html_encode($chosenCategory->name);
        }

        $customFields = '';
        // more than one means that is parent category
        if (count($categories) == 1) {
            $categoryId = $category->category_id;
            $categoryFields = CategoryField::find()->where(['category_id' => $categoryId])->orderBy(['sort_order' => SORT_ASC])->all();
            // retrieve custom fields
            $typeData = [];
            foreach ($categoryFields as $field) {
                $typeData[] = [
                    'type'  => $field->type,
                    'field' => $field,
                ];
            }
            foreach ($typeData as $data) {
                $type = $data['type'];
                $field = $data['field'];
                if (!is_file(\Yii::getAlias('@' . str_replace('\\', '/', $type->class_name) . '.php'))) {
                    continue;
                }
                $className = $type->class_name;
                $component = new $className();
                if (!($component instanceof Type)) {
                    continue;
                }
                $component->field = $field;
                $component->params = [
                    'categoryId' => $categoryId,
                ];
                $component->handleFrontendSearchFormDisplay();
            }
            $customFields = $this->renderPartial('_custom-search');
        }

        $searchModel = new ListingSearch();

        $AdsProvider = $searchModel->categorySearch(request()->queryParams);
        $AdsProvider->query->andWhere(['category_id' => $categories])
            ->orderBy(['promo_expire_at' => SORT_DESC, 'created_at' => SORT_DESC]);
        $AdsProvider->sort = ['defaultOrder' => ['promo_expire_at' => SORT_DESC]];
        $AdsProvider->pagination = [
            'defaultPageSize' => self::ADS_PER_PAGE,
            ];

        // select location details if filter is not empty
        $locationDetails = '';
        $locationDatabase = '';
        if (isset(request()->queryParams['ListingSearch']) && !empty(request()->queryParams['ListingSearch']['location'])) {
            $locationDatabase = $params['ListingSearch']['location'];
            if(strpos(request()->queryParams['ListingSearch']['location'], 'zo-') === 0){
                $zone = Zone::find()->with('country')->where(['zone_id' => substr(request()->queryParams['ListingSearch']['location'],3)])->one();
                if ($zone) {
                    $locationDetails = $zone->name . ', ' . $zone->country->name;
                }
            } else if(strpos(request()->queryParams['ListingSearch']['location'], 'co-') === 0){
                $country = Country::find()->where(['country_id' => substr(request()->queryParams['ListingSearch']['location'],3)])->one();
                if ($country) {
                    $locationDetails = $country->name;
                }
            } else if(strpos(request()->queryParams['ListingSearch']['location'], 'ci-') === 0){
                $location = Location::find()->where(['LIKE', 'city', substr(request()->queryParams['ListingSearch']['location'],3)])->one();
                if ($location) {
                    $locationDetails = $location->city . ', ' . $location->country->name;
                } else {
                    $locationDetails = html_encode(substr(request()->queryParams['ListingSearch']['location'],3));
                }
            }
        }

        $listingAds = $searchModel->mapCategorySearch($params, $categories);

        // set custom fields to display if they have value
        $advanceSearchOptions= [];
        if (isset($params['CategoryField'])) {
            $advanceSearchOptions['collapse'] = ' in';
            $advanceSearchOptions['ariaExpanded'] = 'true';
        } else {
            $advanceSearchOptions['collapse'] = '';
            $advanceSearchOptions['ariaExpanded'] = 'false';
        }

        // set the redirect url for child category with value in the search fields
        $paramsSearch = [];
        if (isset($params['ListingSearch'])) {
            $paramsSearch['ListingSearch'] = $params['ListingSearch'];
            $paramsSearch['key'] = 'ListingSearch';
        } else {
            $paramsSearch['ListingSearch'] = null;
            $paramsSearch['key'] = null;
        }

        app()->view->title = $category->name . ' - ' . options()->get('app.settings.common.siteName', 'EasyAds');

        return $this->render('map-view', [
            'params'                  => $params,
            'searchModel'             => $searchModel,
            'category'                => $category,
            'categories'              => $searchCategories,
            'categoryPlaceholderText' => $categoryPlaceholderText,
            'listingAds'              => $listingAds,
            'customFields'            => $customFields,
            'locationDatabase'        => $locationDatabase,
            'locationDetails'         => $locationDetails,
            'childCategories'         => $childCategories,
            'advanceSearchOptions'    => $advanceSearchOptions,
            'paramsSearch'            => $paramsSearch,
        ]);
    }


    /**
     * Return array(as JSON) of zone name and country name by ajax request
     * in all select2 location in search areas
     *
     * @return array
     */
    public function actionLocation()
    {
        if (!request()->isAjax) {
            return $this->redirect(['/']);
        }

        app()->response->format = Response::FORMAT_JSON;

        return Location::getResultsByTerm(request()->get('term'));
    }

    /**
     * Find category by unique slug
     *
     * @param $slug
     *
     * @return mixed
     * @throws NotFoundHttpException
     */
    protected function findCategoryBySlug($slug)
    {
        if (($category = Category::findOne(['slug' => $slug, 'status' => Category::STATUS_ACTIVE])) !== null) {
            return $category;
        }
        throw new NotFoundHttpException(t('app', 'The requested page does not exist.'));
    }

    /**
     * @return array|Response
     */
    public function actionGetMapLocation()
    {
        if (!request()->isAjax) {
            return $this->redirect(['site/search']);
        }

        response()->format = Response::FORMAT_JSON;

        $locationDatabase = request()->post('locationDatabase');
        $locationDetails = request()->post('locationDetails');

        $mapDetails = ListingSearch::mapCoordinates($locationDetails, $locationDatabase);

        return['result' => 'success', 'content' => $mapDetails];

    }
}