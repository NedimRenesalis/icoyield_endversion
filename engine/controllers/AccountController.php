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

use app\helpers\StringHelper;
use app\models\Conversation;
use app\models\CustomerStore;
use app\models\Listing;
use app\models\ListingFavorite;
use app\models\Invoice;
use app\models\Customer;
use app\models\CustomerForgotForm;
use app\models\CustomerLoginForm;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use app\yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use app\yii\filters\OwnerAccessRule;

/**
 * Class AccountController
 * @package app\controllers
 */
class AccountController extends Controller
{
    const INVOICES_PER_PAGE = 10;

    const MYADS_PER_PAGE = 10;

    const FAVORITES_PER_PAGE = 10;

    const CONVERSATIONS_PER_PAGE = 10;

    /**
     * init
     */
    public function init()
    {
        parent::init();
        $facebook                 = app()->authClientCollection->getClient('facebook');
        $facebook->clientId       = options()->get('app.settings.common.siteFacebookId', '');
        $facebook->clientSecret   = options()->get('app.settings.common.siteFacebookSecret', '');

        $google                   = app()->authClientCollection->getClient('google');
        $google->clientId         = options()->get('app.settings.common.siteGoogleId','');
        $google->clientSecret     = options()->get('app.settings.common.siteGoogleSecret','');

        $linkedIn                 = app()->authClientCollection->getClient('linkedin');
        $linkedIn->clientId       = options()->get('app.settings.common.siteLinkedInId','');
        $linkedIn->clientSecret   = options()->get('app.settings.common.siteLinkedInSecret','');

        $twitter                  = app()->authClientCollection->getClient('twitter');
        $twitter->attributeParams = ['include_email' => 'true'];
        $twitter->consumerKey     = options()->get('app.settings.common.siteTwitterId','');
        $twitter->consumerSecret  = options()->get('app.settings.common.siteTwitterSecret','');

        if (options()->get('app.settings.invoice.disableInvoices', 0) == 1){
            app()->on('app.account.navigation.list', function ($event) {
                $navigation = $event->params['navigation'];
                unset($navigation['invoices']);
                $event->params['navigation'] = $navigation;

            });
        }
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'ownerAccess' => [
                'class'      => AccessControl::className(),
                'only'       => ['generate-invoice-pdf', 'send-invoice'],
                'rules'      => [['actions' => ['generate-invoice-pdf', 'send-invoice']]],
                'ruleConfig' => ['class' => OwnerAccessRule::className()],
                'user'       => 'customer' // to redirect not allowed users to the front-end login not admin
            ],
        ];
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'loginfb'       => [
                'class'             => 'yii\authclient\AuthAction',
                'successCallback'   => [$this, 'fbSuccessCallback'],
                'successUrl'        => url(['account/index'], true),
            ],
            'loginGoogle'   => [
                'class'             => 'yii\authclient\AuthAction',
                'successCallback'   => [$this, 'GoogleSuccessCallback'],
                'successUrl'        => url(['account/index'], true),
            ],
            'loginLinkedIn' => [
                'class'             => 'yii\authclient\AuthAction',
                'successCallback'   => [$this, 'LinkedInSuccessCallback'],
                'successUrl'        => url(['account/index'], true),
            ],
            'loginTwitter' => [
                'class'             => 'yii\authclient\AuthAction',
                'successCallback'   => [$this, 'TwitterSuccessCallback'],
                'successUrl'        => url(['account/index'], true),
            ]
        ];
    }

    /**
     * @param $client
     * @return bool
     */
    public function fbSuccessCallback($client)
    {
        $fbAttributes = $client->getUserAttributes();
        if (empty($fbAttributes)) {
            notify()->addError(t('app', 'Something went wrong!'));
            return false;
        }

        $fbAttributes['first_name'] = (!empty($fbAttributes['name'])) ? explode(' ', $fbAttributes['name'])[0] : null;
        $fbAttributes['last_name'] = (!empty($fbAttributes['name'])) ? explode(' ', $fbAttributes['name'])[1] : null;

        $customer = Customer::findByEmail($fbAttributes['email']);

        if (empty($customer)) {
            $customer               = new Customer();
            $customer->first_name   = $fbAttributes['first_name'];
            $customer->last_name    = $fbAttributes['last_name'];
            $customer->email        = $fbAttributes['email'];
            $customer->password     = StringHelper::random(8);
            $customer->source       = 'Facebook';
            $customer->save(false);

            $customer->sendRegistrationEmail();
        }

        $customer->activate();
        app()->customer->login($customer, 0);
        notify()->addSuccess(t('app', 'Successful action!'));

        return true;
    }

    /**
     * @param $client
     * @return bool
     */
    public function GoogleSuccessCallback($client)
    {
       $googleAttributes = $client->getUserAttributes();

       if(empty($googleAttributes)) {
           notify()->addError(t('app','Something went wrong!'));
       }

       $googleAttributes['displayName'] = explode(' ', $googleAttributes['displayName']);

       $googleAttributes['first_name'] = (!empty($googleAttributes['name']['givenName'])) ? $googleAttributes['name']['givenName'] : null;
       $googleAttributes['last_name'] = (!empty($googleAttributes['name']['familyName'])) ? $googleAttributes['name']['familyName'] : null;

       $customer = Customer::findByEmail($googleAttributes['emails'][0]['value']);

        if (empty($customer)) {
            $customer               = new Customer();
            $customer->first_name   = $googleAttributes['first_name'];
            $customer->last_name    = $googleAttributes['last_name'];
            $customer->email        = $googleAttributes['emails'][0]['value'];
            $customer->password     = StringHelper::random(8);
            $customer->source       = 'Google';
            $customer->save(false);

            $customer->sendRegistrationEmail();
        }

        $customer->activate();
        app()->customer->login($customer, 0);
        notify()->addSuccess(t('app', 'Successful action!'));

        return true;
    }

    /**
     * @param $client
     * @return bool
     */
    public function LinkedInSuccessCallback($client)
    {
       $linkedInAttributes = $client->getUserAttributes();

       if(empty($linkedInAttributes)) {
           notify()->addError(t('app','Something went wrong!'));
       }
       $linkedInAttributes['first_name'] = (!empty($linkedInAttributes['first_name'])) ? $linkedInAttributes['first_name'] : null;
       $linkedInAttributes['last_name'] = (!empty($linkedInAttributes['last_name'])) ? $linkedInAttributes['last_name'] : null;

       $customer = Customer::findByEmail($linkedInAttributes['email-address']);

        if (empty($customer)) {
            $customer               = new Customer();
            $customer->first_name   = $linkedInAttributes['first_name'];
            $customer->last_name    = $linkedInAttributes['last_name'];
            $customer->email        = $linkedInAttributes['email-address'];
            $customer->password     = StringHelper::random(8);
            $customer->source       = 'LinkedIn';
            $customer->save(false);

            $customer->sendRegistrationEmail();
        }

        $customer->activate();
        app()->customer->login($customer, 0);
        notify()->addSuccess(t('app', 'Successful action!'));

        return true;
    }

    /**
     * @param $client
     * @return bool
     */
    public function TwitterSuccessCallback($client)
    {
       $twitterAttributes = $client->getUserAttributes();

       if(empty($twitterAttributes)) {
           notify()->addError(t('app','Something went wrong!'));
       }

       $twitterAttributes['name'] = explode(' ', $twitterAttributes['name']);

       $twitterAttributes['first_name'] = (!empty($twitterAttributes['name'])) ? $twitterAttributes['name'][0] : null;
       $twitterAttributes['last_name'] = (!empty($twitterAttributes['name'])) ? $twitterAttributes['name'][1] : null;

       $customer = Customer::findByEmail($twitterAttributes['email']);

        if (empty($customer)) {
            $customer               = new Customer();
            $customer->first_name   = $twitterAttributes['first_name'];
            $customer->last_name    = $twitterAttributes['last_name'];
            $customer->email        = $twitterAttributes['email'];
            $customer->password     = StringHelper::random(8);
            $customer->source       = 'Twitter';
            $customer->save(false);

            $customer->sendRegistrationEmail();
        }

        $customer->activate();
        app()->customer->login($customer, 0);
        notify()->addSuccess(t('app', 'Successful action!'));

        return true;
    }

    /**
     * @return Response
     */
    public function actionIndex()
    {
        if (app()->customer->isGuest == true) {
            return $this->redirect(['account/login']);
        } else {
            return $this->redirect(['account/my-listings']);
        }
    }

    /**
     * @return string|Response
     */
    public function actionMyListings()
    {
        if (app()->customer->isGuest == true) {
            return $this->redirect(['account/index']);
        }

        $myAdsProvider = new ActiveDataProvider([
            'query'      => Listing::find()->where(['customer_id' => app()->customer->identity->id]),
            'sort'       => ['defaultOrder' => ['listing_id' => SORT_DESC]],
            'pagination' => [
                'defaultPageSize' => self::MYADS_PER_PAGE,
            ],
        ]);

        app()->view->title = t('app','My Ads') . ' - ' . options()->get('app.settings.common.siteName', 'EasyAds');

        return $this->render('my-listings', ['myAdsProvider' => $myAdsProvider]);
    }

    /**
     * @return string|Response
     */
    public function actionFavorites()
    {
        if (app()->customer->isGuest == true) {
            return $this->redirect(['account/index']);
        }
        $favoritesProvider = new ActiveDataProvider([
            'query'      => ListingFavorite::find()->Where(['customer_id' => app()->customer->identity->id]
            ),
            'sort'       => ['defaultOrder' => ['favorite_id' => SORT_DESC]],
            'pagination' => [
                'defaultPageSize' => self::FAVORITES_PER_PAGE,
            ],
        ]);

        app()->view->title = t('app','My Favorites') . ' - ' . options()->get('app.settings.common.siteName', 'EasyAds');

        return $this->render('favorites', ['favoritesProvider' => $favoritesProvider]);
    }

    /**
     * Action to generate list of conversations
     *
     * @return string|Response
     */
    public function actionConversations()
    {
        if (app()->customer->isGuest == true) {
            return $this->redirect(['account/index']);
        }

        // active conversations
        $activeConversationsProvider = new ActiveDataProvider([
            'query'      => Conversation::find()->with(['listing'])->where('status = :status AND (seller_id = :customer_id OR buyer_id = :customer_id)', [
                'status'      => Conversation::CONVERSATION_STATUS_ACTIVE,
                'customer_id' => app()->customer->identity->id,
            ]),
            'sort'       => ['defaultOrder' => ['updated_at' => SORT_DESC]],
            'pagination' => [
                'defaultPageSize' => self::CONVERSATIONS_PER_PAGE,
            ],
        ]);

        // archived conversations
        $archivedConversationsProvider = new ActiveDataProvider([
            'query'      => Conversation::find()->with(['listing'])->where('status = :status AND (seller_id = :customer_id OR buyer_id = :customer_id)', [
                'status'      => Conversation::CONVERSATION_STATUS_ARCHIVED,
                'customer_id' => app()->customer->identity->id,
            ]),
            'sort'       => ['defaultOrder' => ['updated_at' => SORT_DESC]],
            'pagination' => [
                'defaultPageSize' => self::CONVERSATIONS_PER_PAGE,
            ],
        ]);

        app()->view->title = t('app','Inbox') . ' - ' . options()->get('app.settings.common.siteName', 'EasyAds');

        return $this->render('conversations', [
            'activeConversationsProvider'   => $activeConversationsProvider,
            'archivedConversationsProvider' => $archivedConversationsProvider
        ]);
    }

    /**
     * Render list of invoices of current user
     *
     * @return string
     */
    public function actionInvoices()
    {
        if (options()->get('app.settings.invoice.disableInvoices', 0) == 1) {
            return $this->redirect(['account/index']);
        }

        if (app()->customer->isGuest == true) {
            return $this->redirect(['account/index']);
        }

        $invoicesProvider = new ActiveDataProvider([
            'query'      => Invoice::find()->joinWith([
                'order' => function ($query) {
                    $query->onCondition(['customer_id' => app()->customer->identity->id]);
                },
            ], true, 'INNER JOIN'),
            'sort'       => ['defaultOrder' => ['invoice_id' => SORT_DESC]],
            'pagination' => [
                'defaultPageSize' => self::INVOICES_PER_PAGE,
            ],
        ]);

        app()->view->title = t('app','My Invoices') . ' - ' . options()->get('app.settings.common.siteName', 'EasyAds');

        return $this->render('invoices', ['invoicesProvider' => $invoicesProvider]);
    }

    /**
     * Generate invoice pdf
     *
     * @param $id
     * @return mixed
     */
    public function actionGenerateInvoicePdf($id)
    {
        if (options()->get('app.settings.invoice.disableInvoices', 0) == 1) {
            return false;
        }

        return app()->generateInvoicePdf->generate($id);
    }

    /**
     * Send invoice by email
     *
     * @param $id
     * @return Response
     */
    public function actionSendInvoice($id)
    {
        if (options()->get('app.settings.invoice.disableInvoices', 0 ) == 1) {
            return false;
        }

        if (app()->sendInvoice->send($id)) {
            notify()->addSuccess(t('app', 'Invoice was sent successfully!'));
        } else {
            notify()->addError(t('app', 'Something went wrong!'));
        }

        return $this->redirect(['account/invoices']);
    }

    /**
     * @return string|Response
     */
    public function actionInfo()
    {
        if (app()->customer->isGuest == true) {
            return $this->redirect(['account/index']);
        }

        $id = app()->customer->id;

        $modelAbout = $this->findModel($id);

        $modelPassword = clone $modelAbout;
        $modelPassword->scenario = 'update_password';

        $modelEmail = clone $modelAbout;
        $modelEmail->scenario = 'update_email';

        $modelCompany = CustomerStore::findOne(['customer_id' => $id]);
        if (empty($modelCompany)) {
            $modelCompany = new CustomerStore();
        }

        app()->view->title = t('app','Account Info') . ' - ' . options()->get('app.settings.common.siteName', 'EasyAds');

        /* render the view */

        return $this->render('info', [
            'modelAbout'    => $modelAbout,
            'modelPassword' => $modelPassword,
            'modelEmail'    => $modelEmail,
            'modelCompany'  => $modelCompany,
        ]);
    }

    /**
     * @return array|Response
     */
    public function actionUpdateInfoAbout()
    {
        /* allow only ajax calls */
        if (!request()->isAjax) {
            return $this->redirect(['account/index']);
        }

        /* set the output to json */
        response()->format = Response::FORMAT_JSON;

        $id = app()->customer->identity->id;
        $model = Customer::findOne($id);
        $model->scenario = Customer::SCENARIO_UPDATE;
        $model->load(request()->post());

        if (!$model->save()) {
            return ['result' => 'error', 'errors' => $model->getErrors()];
        }

        return ['result' => 'success', 'msg' => t('app', 'Thanks for sharing! Your information has been updated.')];
    }

    /**
     * @return array|Response
     */
    public function actionUpdateCompany()
    {
        /* allow only ajax calls */
        if (!request()->isAjax) {
            return $this->redirect(['account/index']);
        }

        /* set the output to json */
        response()->format = Response::FORMAT_JSON;

        $id = app()->customer->identity->id;
        $model = Customer::findOne($id);
        $model->scenario = Customer::SCENARIO_UPDATE;

        $store = CustomerStore::find()->where(['customer_id' => $id])->one();
        if (empty($store)) {
            $store = new CustomerStore();
        }

        if (request()->post()) {
            $transaction = db()->beginTransaction();
            $error = false;
            try {
                $groupId = ArrayHelper::getValue(request()->post('Customer'), 'group_id');
                $model->group_id = $groupId;
                $model->save(false);

                if ($groupId == 1) {
                    $store->delete();
                } else {
                    $store->attributes = request()->post('CustomerStore');
                    $store->customer_id = $id;
                    $store->status = CustomerStore::STATUS_ACTIVE;
                    $store->save();
                }

                $transaction->commit();
            } catch (\Exception $e) {
                return ['result' => 'error', 'msg' => $e->getMessage()];
                $error = true;
                $transaction->rollBack();
            }
            if (!$error) {
                notify()->addSuccess(t('app', 'Thanks for sharing! Your information has been updated.'));
                return $this->redirect(['/account/info']);
            }
        }
    }

    /**
     * @return array|Response
     */
    public function actionUpdatePassword()
    {
        /* allow only ajax calls */
        if (!request()->isAjax) {
            return $this->redirect(['account/index']);
        }

        /* set the output to json */
        response()->format = Response::FORMAT_JSON;

        $id = app()->customer->id;
        $model = Customer::findOne($id);
        $model->scenario = 'update_password';
        $model->load(request()->post());

        if (!$model->save()) {
            return ['result' => 'error', 'errors' => $model->getErrors()];
        }

        return ['result' => 'success', 'msg' => t('app', 'Thanks for sharing! Your information has been updated.')];
    }

    /**
     * @return array|Response
     */
    public function actionUpdateEmail()
    {
        /* allow only ajax calls */
        if (!request()->isAjax) {
            return $this->redirect(['account/index']);
        }

        /* set the output to json */
        response()->format = Response::FORMAT_JSON;

        $id = app()->customer->id;
        $model = Customer::findOne($id);
        $model->scenario = 'update_email';
        $model->load(request()->post());

        if (!$model->save()) {
            return ['result' => 'error', 'errors' => $model->getErrors()];
        }

        return ['result' => 'success', 'msg' => t('app', 'Thanks for sharing! Your information has been updated.')];
    }

    /**
     * @return Response
     */
    public function actionTerminateAccount()
    {
        /* allow only ajax calls */
        if (!request()->isAjax) {
            return $this->redirect(['account/index']);
        }

        /* set the output to json */
        response()->format = Response::FORMAT_JSON;

        $id = app()->customer->id;
        $model = Customer::findOne($id);

        $model->deactivate();

        app()->customer->logout();
        notify()->addSuccess(t('app', 'We are sorry to see you leave'));

        return $this->redirect(['/']);
    }

    /**
     * @return string|Response
     */
    public function actionJoin()
    {
        if (app()->customer->isGuest == false) {
            return $this->redirect(['account/index']);
        }
        $model = new Customer([
            'scenario' => Customer::SCENARIO_CREATE
        ]);

        app()->view->title = t('app','Join') . ' - ' . options()->get('app.settings.common.siteName', 'EasyAds');

        if ($model->load(request()->post()) && $model->save()) {
            $model->sendRegistrationEmail();
            app()->customer->login($model, 0);
            notify()->addSuccess(t('app', 'Your account was created successfully!'));

            return $this->redirect(['/']);
        }

        return $this->render('join', [
            'action' => 'create',
            'model'  => $model,
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionLogin()
    {
        if (app()->customer->isGuest == false) {
            return $this->redirect(['account/index']);
        }
        $model = new CustomerLoginForm();

        $LoginForm = request()->post('CustomerLoginForm');
        $customer = Customer::findByEmail($LoginForm['email']);

        app()->view->title = t('app','Login') . ' - ' . options()->get('app.settings.common.siteName', 'EasyAds');

        /* if form is submitted */
        if ($model->load(request()->post()) && $model->login()) {
            if ($customer && $customer->status == Customer::STATUS_DEACTIVATED) {
                notify()->addSuccess(t('app', 'Your email is linked to a deactivated account, We reactivated your account now.'));
                $customer->activate();
            }

            return $this->redirect(['account/index']);
        }

        /* render the view */
        return $this->render('login', [
            'model' => $model,
        ]);

    }

    /**
     * @return string|Response
     */
    public function actionForgot()
    {

        $model = new CustomerForgotForm();

        /* if form is submitted */
        if ($model->load(request()->post()) && $model->sendEmail()) {
            notify()->addSuccess(t('app', 'Please check your email for confirmation!'));

            return $this->redirect(['account/index']);
        }

        app()->view->title = t('app','Forgot password') . ' - ' . options()->get('app.settings.common.siteName', 'EasyAds');

        /* render the view */

        return $this->render('forgot', [
            'model' => $model,
        ]);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function actionReset_password($key)
    {
        if (!($model = Customer::findByPasswordResetKey($key))) {
            notify()->addError(t('app', 'Invalid password reset key!'));

            return $this->redirect(['account/index']);
        }

        $password = security()->generateRandomString(12);
        $model->password = $password;
        $model->password_reset_key = null;
        $model->save(false);

        notify()->addSuccess(t('app', 'Your new password is: {password}', [
            'password' => sprintf('<b>%s</b>', $password),
        ]));

        return $this->redirect(['account/index']);
    }

    /**
     * @return Response
     */
    public function actionLogout()
    {
        app()->customer->logout();
        notify()->addSuccess(t('app', 'Successfully logged out!'));

        return $this->redirect(app()->customer->loginUrl);
    }

    /**
     * @return Response
     */
    public function actionImpersonate()
    {
        if (!session()->get('impersonating_customer')) {
            return $this->redirect(['/account']);
        }

        $backURL = session()->get('impersonating_customer_back_url');

        app()->customer->logout();

        if (!empty($backURL)) {
            return $this->redirect($backURL);
        }
        return $this->redirect(['/admin/customers']);
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
        throw new NotFoundHttpException(t('app', 'The requested page does not exist.'));
    }
}