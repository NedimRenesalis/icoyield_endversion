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
use app\models\ContactForm;
use app\models\Country;
use app\models\ListingSearch;
use app\models\Category;
use app\models\Location;
use app\models\Zone;
use app\yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller
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
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ListingSearch();
        $topCatsWithFirstLevelChildren = Category::getTopCatsWithFirstLevelChildren();

        // Meta tags
        app()->view->registerMetaTag([
            'name'    => 'keywords',
            'content' => options()->get('app.settings.common.siteKeywords', 'EasyAds')
        ]);
        app()->view->registerMetaTag([
            'name'    => 'description',
            'content' => options()->get('app.settings.common.siteDescription', 'EasyAds')
        ]);

        app()->view->title = options()->get('app.settings.common.siteName', 'EasyAds');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'categories'  => $topCatsWithFirstLevelChildren
        ]);
    }

    /**
     * Displays search page.
     *
     * @return string
     */
    public function actionSearch()
    {
        $searchModel = new ListingSearch();
        $categories = Category::find()->where(['status' => Category::STATUS_ACTIVE])->orderBy(['sort_order' => SORT_ASC])->all();

        $categoryPlaceholderText = t('app', 'Choose Category');
        if ($slug = request()->get('slug')) {
            $chosenCategory = Category::findCategoryBySlug($slug);
            if ($chosenCategory) {
                $categoryPlaceholderText = Icon::make($chosenCategory->icon) . ' ' .html_encode($chosenCategory->name);
            }
        }

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
                    $locationDetails = $zone->name;
                }
            } else if(strpos(request()->queryParams['ListingSearch']['location'], 'co-') === 0){
                $country = Country::find()->where(['country_id' => substr(request()->queryParams['ListingSearch']['location'],3)])->one();
                if ($country) {
                    $locationDetails = $country->name;
                }
            } else if(strpos(request()->queryParams['ListingSearch']['location'], 'ci-') === 0){
                $location = Location::find()->where(['location_id' => substr(request()->queryParams['ListingSearch']['location'],3)])->one();
                if ($location) {
                    $locationDetails = $location->city;
                } else {
                    $locationDetails = html_encode(substr(request()->queryParams['ListingSearch']['location'],3));
                }
            }
        }

        app()->view->title = t('app', 'Search') . ' - ' . options()->get('app.settings.common.siteName', 'EasyAds');

        return $this->render('search-results', [
            'searchModel'               => $searchModel,
            'categories'                => $categories,
            'categoryPlaceholderText'   => $categoryPlaceholderText,
            'adsProvider'               => $AdsProvider,
            'locationDetails'           => $locationDetails,
            'isNothingFound'            => !$AdsProvider->getCount(),
        ]);
    }


    /**
     * Displays map search page.
     *
     * @return string
     */
    public function actionSearchMap()
    {
        $searchModel = new ListingSearch();
        $categories = Category::find()->where(['status' => Category::STATUS_ACTIVE])->all();
        $params = request()->queryParams['ListingSearch'];

        if (!$params) {
            $this->redirect(url(['/site/search']));
        }

        $categoryPlaceholderText = t('app', 'Choose Category');
        if ($slug = request()->get('slug')) {
            $chosenCategory = Category::findCategoryBySlug($slug);
            if ($chosenCategory) {
                $categoryPlaceholderText = Icon::make($chosenCategory->icon) . ' ' .html_encode($chosenCategory->name);
            }
        }

        // ads
        $AdsProvider = $searchModel->categorySearch(request()->queryParams);
        $AdsProvider->query->andWhere(['category_id' => $categories])
            ->orderBy(['promo_expire_at' => SORT_DESC, 'created_at' => SORT_DESC]);
        $AdsProvider->sort = ['defaultOrder' => ['promo_expire_at' => SORT_DESC]];
        $AdsProvider->pagination = [
            'defaultPageSize' => self::ADS_PER_PAGE,
        ];

        // select location details if filter is not empty
        $locationDatabase='';
        $locationDetails = '';
        if (isset($params) && !empty($params['location'])) {
            $locationDatabase = $params['location'];
            if(strpos($params['location'], 'zo-') === 0){
                $zone = Zone::find()->with('country')->where(['zone_id' => substr($params['location'],3)])->one();
                if ($zone) {
                    $locationDetails = $zone->name;
                }
            } else if(strpos($params['location'], 'co-') === 0){
                $country = Country::find()->where(['country_id' => substr($params['location'],3)])->one();
                if ($country) {
                    $locationDetails = $country->name;
                }
            } else if(strpos($params['location'], 'ci-') === 0){
                $location = Location::find()->where(['location_id' => substr($params['location'],3)])->one();
                if ($location) {
                    $locationDetails = $location->city;
                } else {
                    $locationDetails = html_encode(substr($params['location'],3));
                }
            }

            $listingAds = $searchModel->mapSearch($params);
        }

        app()->view->title = t('app', 'Search') . ' - ' . options()->get('app.settings.common.siteName', 'EasyAds');

        return $this->render('search-results-map', [
            'listingAds'                => $listingAds,
            'searchModel'               => $searchModel,
            'categories'                => $categories,
            'categoryPlaceholderText'   => $categoryPlaceholderText,
            'locationDatabase'          => $locationDatabase,
            'locationDetails'           => $locationDetails,
        ]);

    }

    /**
     * @return string
     */
    public function actionOffline()
    {
        if(options()->get('app.settings.common.siteStatus', 1) == 1) {
            $this->redirect(['/']);
        }

        app()->view->title = t('app', 'Offline') . ' - ' . options()->get('app.settings.common.siteName', 'EasyAds');

        return $this->render('offline', [
           'message' =>  options()->get('app.settings.common.siteOfflineMessage', t('app', 'Application is offline, please try again later!'))
        ]);
    }

    /**
     * @param $url
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionRedirect($url)
    {
        if (substr($url, -1) == '/') {
            return $this->redirect(app()->getRequest()->getBaseUrl() . '/' . rtrim($url, '/'), 301);
        }
        throw new NotFoundHttpException(t('app', 'The requested page does not exist.'));
    }

    /**
     * Display contact page
     */
    public function actionContact()
    {
        $model = new ContactForm();

        app()->view->title = t('app', 'Contact') . ' - ' . options()->get('app.settings.common.siteName', 'EasyAds');

        app()->view->registerMetaTag([
            'name'    => 'keywords',
            'content' => options()->get('app.content.contact.keywords', 'Default')
        ]);
        app()->view->registerMetaTag([
            'name'    => 'description',
            'content' => options()->get('app.content.contact.description', 'Default'),
        ]);

        return $this->render('contact',[
            'model' => $model
        ]);
    }

    /**
     * Action to send contact form message
     */
    public function actionSendContactForm()
    {
        $messageData = request()->post('ContactForm');
        $contactEmail = options()->get('app.content.contact.contactEmail','');
        $ccToSender = options()->get('app.content.contact.senderEmail', 0);

        if (!empty($ccToSender)) {
            @app()->mailSystem->add('contact-form-message', [
                'contact_email'     => $messageData['email'],
                'sender_full_name'  => $messageData['fullName'],
                'sender_phone'      => $messageData['phone'],
                'sender_email'      => $messageData['email'],
                'sender_message'    => $messageData['message'],
                'reply_to'          => $messageData['email'],
            ]);
        }

        $isSuccess = app()->mailSystem->add('contact-form-message', [
            'contact_email'     => $contactEmail,
            'sender_full_name'  => $messageData['fullName'],
            'sender_phone'      => $messageData['phone'],
            'sender_email'      => $messageData['email'],
            'sender_message'    => $messageData['message'],
            'reply_to'          => $messageData['email'],
        ]);

        if ($isSuccess) {
            notify()->addSuccess(t('app', 'Message was successfully sent!'));
        } else {
            notify()->addError(t('app', 'Something went wrong, message was not sent!'));
        }

        return $this->redirect('contact');

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