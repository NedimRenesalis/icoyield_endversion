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

/**
 * Class MailAccount
 * @package app\models
 */
class MailAccount extends \app\models\auto\MailAccount
{
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_name', 'hostname', 'username', 'password', 'port', 'from', 'used_for'], 'required'],
            [['port', 'timeout'], 'integer'],
            [['account_name', 'hostname', 'username', 'password', 'encryption', 'from', 'reply_to'], 'string', 'max' => 255],
            ['encryption', 'in', 'range' => array_keys($this->getEncryptionsList())],
            [['from', 'reply_to'], 'email'],
            [['username'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'account_name'  => t('app', 'Account Name'),
            'hostname'      => t('app', 'Hostname'),
            'username'      => t('app', 'Username'),
            'password'      => t('app', 'Password'),
            'port'          => t('app', 'Port'),
            'encryption'    => t('app', 'Encryption'),
            'timeout'       => t('app', 'Timeout'),
            'from'          => t('app', 'From'),
            'reply_to'      => t('app', 'Replay To'),
            'used_for'      => t('app', 'Template Types'),
            'created_at'    => t('app', 'Created At'),
            'updated_at'    => t('app', 'Updated At'),
        ];
    }

    /**
     * Get list of encryptions
     *
     * @return array
     */
    public function getEncryptionsList()
    {
        return [
            ''    => t('app', 'None'),
            'tls' => t('app', 'TLS'),
            'ssl' => t('app', 'SSL'),
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        // in order to save multiple select
        $templates = implode(",", $this->used_for);
        $this->used_for = $templates;

        if (!empty($this->hostname) && !empty($this->username) && !empty($this->password)) {
            if (!$this->checkAccount()) {
                return false;
            }
        }

        return parent::beforeSave($insert);
    }

    /**
     * Check current account
     *
     * @return bool
     */
    public function checkAccount()
    {
        try{
            $transport = \Swift_SmtpTransport::newInstance($this->hostname, $this->port, $this->encryption);
            $transport->setUsername($this->username);
            $transport->setPassword($this->password);
            $mailer = \Swift_Mailer::newInstance($transport);
            $mailer->getTransport()->start();

            return true;
        } catch (\Swift_TransportException $e) {
            notify()->addError(t('app', 'Mail account error: {errorMessage}', [
                'errorMessage' => $e->getMessage(),
            ]));

            return false;
        } catch (\Exception $e) {
            notify()->addError(t('app', 'Mail account error: {errorMessage}', [
                'errorMessage' => $e->getMessage(),
            ]));

            return false;
        }
    }

    /**
     * Get list of used types of template
     *
     * @param null $excludeId
     * @return array
     */
    public static function getListOfUsedTemplateTypes($excludeId = null)
    {
        $result = [];

        $query = self::find()->select('used_for')->indexBy('used_for');
        if ($excludeId) {
            $query->where(['<>','account_id', $excludeId]);
        }

        $accounts = $query->column();

        foreach ($accounts as $usedTemplates) {
            $used = explode(',', $usedTemplates);
            foreach ($used as $templateId) {
                $result[$templateId] = ['disabled' => true];
            }
        }

        return $result;
    }

    /*
     * TODO
    public function sendEmail($params)
    {

    }
    */
}
