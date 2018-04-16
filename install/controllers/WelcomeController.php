<?php defined('INST_INSTALLER_PATH') || exit('No direct script access allowed');

/**
 *
 * @package    EasyAds
 * @author     CodinBit <contact@codinbit.com>
 * @link       https://www.easyads.io
 * @copyright  2017 EasyAds (https://www.easyads.io)
 * @license    https://www.easyads.io
 * @since      1.3
 */

/**
 * Class WelcomeController
 */
class WelcomeController extends Controller
{
    public function actionIndex()
    {
        // start clean
        $_SESSION = array();

        $this->validateRequest();

        if (getSession('welcome')) {
            redirect('index.php?route=requirements');
        }

        $this->data['marketPlaces'] = $this->getMarketPlaces();

        $this->data['pageHeading'] = 'Welcome';
        $this->data['breadcrumbs'] = array(
            'Welcome' => 'index.php?route=welcome',
        );

        $this->render('welcome');
    }

    protected function validateRequest()
    {

        if (!getPost('next')) {
            return;
        }

        $firstName      = getPost('first_name');
        $lastName       = getPost('last_name');
        $email          = getPost('email');
        $marketPlace    = getPost('market_place');
        $purchaseCode   = getPost('purchase_code');

        if (empty($firstName)) {
            $this->addError('first_name', 'Please supply your first name!');
        } elseif (strlen($firstName) < 2 || strlen($firstName) > 100) {
            $this->addError('first_name', 'First name length must be between 2 and 100 chars!');
        }

        if (empty($lastName)) {
            $this->addError('last_name', 'Please supply your last name!');
        } elseif (strlen($lastName) < 2 || strlen($lastName) > 100) {
            $this->addError('last_name', 'Last name length must be between 2 and 100 chars!');
        }

        if (empty($email)) {
            $this->addError('email', 'Please supply your email address!');
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addError('email', 'Please provide a valid email address!');
        }

        if (empty($marketPlace)) {
            $this->addError('market_place', 'Please enter the market place from where you have bought the license!');
        }

        if ($marketPlace == 'envato' && empty($purchaseCode)) {
            $this->addError('purchase_code', 'Please enter the purchase code!');
        }

        if ($this->hasErrors()) {
            return $this->addError('general', 'Your form has a few errors, please fix them and try again!');
        }

        // license check.
        $postData = array(
            'first_name'        => $firstName,
            'last_name'         => $lastName,
            'email'             => $email,
            'purchase_code'     => $purchaseCode,
            'order_count'       => 0,
            'product_id'        => 1,
            'product_domain'    => $_SERVER['HTTP_HOST'],
            'marketplace'       => $marketPlace
        );

        $url        = 'https://api.codinbit.com/v1/license';
        $request    = \app\helpers\CommonHelper::simpleCurlPost($url, $postData);

        if ($request['status'] == 'error') {
            return $this->addError('general', $request['message']);
        }

        $response = @json_decode($request['message'], true);

        if (!empty($response)) {
            if (isset($response['message'])) {
                return $this->addError('general', $response['message']);
            }
            if (isset($response['error']) && is_string($response['error'])) {
                return $this->addError('general', $response['error']);
            }
            return $this->addError('general', 'Invalid response, please contact support!');
        }

        setSession('license_data', $postData);
        setSession('welcome', 1);
    }

    public function getMarketPlaces()
    {
        return array(
            'envato'    => 'Envato Market Places',
//            'codinbit'  => 'CodinBit Website',
        );
    }
}