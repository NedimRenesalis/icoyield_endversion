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

namespace app\components\mail\template;

use app\models\Customer;

class TemplateTypeCustomer implements TemplateTypeInterface
{
    const TEMPLATE_TYPE = 4;

    /**
     * @var array list of variables of template
     */
    protected $varsList = [
        'customer_first_name' => 'Customer First Name',
        'customer_email'      => 'Customer Email',
        'confirmation_url'    => 'Confirmation url',
        'login_url'           => 'Login url',
    ];

    protected $customerEmail;

    public function __construct(array $data)
    {
        if (!empty($data)) {
            $this->customerEmail = $data['customer_email'];
        }
    }

    public function populate()
    {
        /**
         * @var Customer $customerModel
         */
        $customerModel = Customer::findByEmail($this->customerEmail);
        $this->recipient = $customerModel->email;

        $confirmationUrl = url(['account/reset_password', 'key' => $customerModel->password_reset_key], true);
        $loginUrl = trim(str_replace('/index.php', '', options()->get('app.settings.urls.siteUrl', '')), '/') . '/account/login';

        return [
            'customer_first_name' => $customerModel->first_name,
            'customer_email'      => $customerModel->email,
            'confirmation_url'    => $confirmationUrl,
            'login_url'           => $loginUrl,
        ];
    }

    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @return array
     */
    public function getVarsList()
    {
        return $this->varsList;
    }
}