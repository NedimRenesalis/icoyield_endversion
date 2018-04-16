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

use app\models\User;

class TemplateTypeAdmin implements TemplateTypeInterface
{
    const TEMPLATE_TYPE = 3;

    /**
     * @var array list of variables of template
     */
    protected $varsList = [
        'admin_first_name'      => 'Admin First Name',
        'confirmation_url'      => 'Confirmation URL',
        'reset_password_url'    => 'Reset Password URL',
        'admin_email'           => 'Admin Email',
    ];

    protected $adminEmail;

    public function __construct(array $data)
    {
        if (!empty($data)) {
            $this->adminEmail = $data['admin_email'];
        }
    }

    public function populate()
    {
        /**
         * @var User $adminUserModel
         */
        $adminUserModel = User::findByEmail($this->adminEmail);
        $this->recipient = $adminUserModel->email;

        $confirmationUrl = url(['/admin/reset_password', 'key' => $adminUserModel->password_reset_key], true);
        $resetPasswordUrl = url(['/admin/forgot'], true);

        return [
            'admin_first_name'      => $adminUserModel->first_name,
            'confirmation_url'      => $confirmationUrl,
            'reset_password_url'    => $resetPasswordUrl,
            'admin_email'           => $adminUserModel->email,
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