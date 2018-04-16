<?php defined('INST_INSTALLER_PATH') || exit('No direct script access allowed');

/**
 *
 * @package    EasyAds
 * @author     CodinBit <contact@codinbit.com>
 * @link       https://www.easyads.io
 * @copyright  2017 EasyAds (https://www.easyads.io)
 * @license    https://www.easyads.io
 * @since      1.0
 */

/**
 * Class AdminController
 */
class AdminController extends Controller
{
    /**
     * Main action
     */
    public function actionIndex()
    {
        if (!getSession('database') || !getSession('license_data')) {
            redirect('index.php?route=database');
        }

        $this->validateRequest();

        if (getSession('admin')) {
            redirect('index.php?route=cron');
        }

        $this->data['pageHeading'] = 'Admin account';
        $this->data['breadcrumbs'] = array(
            'Admin account' => 'index.php?route=admin',
        );

        $this->render('admin');
    }

    /**
     * @return $this
     */
    protected function validateRequest()
    {
        if (!getPost('next')) {
            return $this;
        }

        $firstName  = getPost('first_name');
        $lastName   = getPost('last_name');
        $email      = getPost('email');
        $password   = getPost('password');

        if (empty($firstName)) {
            $this->addError('first_name', 'Please supply your first name!');
        } elseif (strlen($firstName) < 2 || strlen($firstName) > 100) {
            $this->addError('first_name', 'First name length must be between 2 and 100 chars!');
        } elseif (!preg_match('/^([a-z\s\-]+)$/i', $firstName)) {
            $this->addError('first_name', 'First name must contain only letters, spaces and dashes!');
        }

        if (empty($lastName)) {
            $this->addError('last_name', 'Please supply your last name!');
        } elseif (strlen($lastName) < 2 || strlen($lastName) > 100) {
            $this->addError('last_name', 'Last name length must be between 2 and 100 chars!');
        } elseif (!preg_match('/^([a-z\s\-]+)$/i', $lastName)) {
            $this->addError('last_name', 'Last name must contain only letters, spaces and dashes!');
        }

        if (empty($email)) {
            $this->addError('email', 'Please supply your email address!');
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addError('email', 'Please provide a valid email address!');
        }

        if (empty($password)) {
            $this->addError('password', 'Please supply your password!');
        } elseif (strlen($password) < 6 || strlen($lastName) > 100) {
            $this->addError('password', 'Password length must be between 6 and 100 chars!');
        }

        if ($this->hasErrors()) {
            return $this->addError('general', 'Your form has a few errors, please fix them and try again!');
        }

        $app  = appInstance();

        $user = new \app\models\User([
            'scenario' => \app\models\User::SCENARIO_CREATE
        ]);
        $user->first_name       = $firstName;
        $user->last_name        = $lastName;
        $user->email            = $email;
        $user->fake_password    = $password;
        $user->group_id         = 1;
        $user->status           = "active";

        if (!$user->save(false)) {
            return $this->addError('general', 'Unable to create specified user!');
        }

        setSession('admin', 1);

        return $this;
    }
}
