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

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Class ForgotForm
 * @package app\models
 */
class CustomerForgotForm extends Model
{
    /**
     * @var string
     */
    public $email = '';

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'email'],
            [['email'], 'validateEmail'],
        ];
    }

    /**
     * Validates the email.
     * This method serves as the inline validation for email.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateEmail($attribute, $params)
    {
        if ($this->hasErrors()) {
            return;
        }

        $model = Customer::findOne([
            'email'  => $this->$attribute,
            'status' => 'active'
        ]);

        if (empty($model)) {
            $this->addError('email', t('app', 'Invalid email address!'));
        }
    }

    /**
     * @return bool
     */
    public function sendEmail()
    {
        if (!$this->validate()) {
            return false;
        }

        if (!($model = Customer::findByEmail($this->email))) {
            return false;
        }

        $model->password_reset_key = security()->generateRandomString();
        $model->save(false);

        return app()->mailSystem->add('customer-password-change-request', ['customer_email' => $model->email]);
    }
}
