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


class TemplateTypeContact implements TemplateTypeInterface
{
    const TEMPLATE_TYPE = 6;

    /** Full name, Phone, Email, Message
     * @var array list of variables of template
     */
    protected $varsList = [
        'sender_full_name'    => 'Sender Full Name',
        'sender_phone'        => 'Sender Phone',
        'sender_email'        => 'Sender Email',
        'sender_message'      => 'Sender Message',
    ];

    protected $contactEmail;
    protected $senderFullName;
    protected $senderPhone;
    protected $senderEmail;
    protected $senderMessage;
    protected $replyTo;


    public function __construct(array $data)
    {
        if (!empty($data)) {
            $this->contactEmail = $data['contact_email'];
            $this->senderFullName = $data['sender_full_name'];
            $this->senderPhone = $data['sender_phone'];
            $this->senderEmail = $data['sender_email'];
            $this->senderMessage = $data['sender_message'];
            $this->replyTo = $data['reply_to'];

        }
    }

    public function populate()
    {
        return[
            'sender_full_name'  => $this->senderFullName,
            'sender_phone'      => $this->senderPhone,
            'sender_email'      => $this->senderEmail,
            'sender_message'    => $this->senderMessage,
            'reply_to'          => $this->replyTo,
        ];

    }

    public function getRecipient()
    {
        return $this->contactEmail;
    }

    /**
     * @return array
     */
    public function getVarsList()
    {
        return $this->varsList;
    }
}