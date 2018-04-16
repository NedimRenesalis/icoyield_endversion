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

use \app\yii\base\Event;

class TemplateType
{
    protected static $templateTypes = [
        TemplateTypeAd::TEMPLATE_TYPE             => 'app\components\mail\template\TemplateTypeAd',
        TemplateTypeInvoice::TEMPLATE_TYPE        => 'app\components\mail\template\TemplateTypeInvoice',
        TemplateTypeAdmin::TEMPLATE_TYPE          => 'app\components\mail\template\TemplateTypeAdmin',
        TemplateTypeCustomer::TEMPLATE_TYPE       => 'app\components\mail\template\TemplateTypeCustomer',
        TemplateTypeAdConversation::TEMPLATE_TYPE => 'app\components\mail\template\TemplateTypeAdConversation',
        TemplateTypeContact::TEMPLATE_TYPE        => 'app\components\mail\template\TemplateTypeContact'
    ];

    protected static $templateTypesLabel = [];

    /**
     * Templates Factory
     *
     * @param       $type
     * @param array $data
     *
     * @return mixed
     * @throws \Exception
     */
    public static function create($type, array $data = [])
    {
        $event = new Event();
        $event->params = [
            'templateTypes' => self::$templateTypes,
        ];
        app()->trigger('app.components.mailTemplate.templatesGathering', $event);

        $class = $event->params['templateTypes'][$type];
        if (class_exists($class)) {
            return new $class($data);
        } else {
            throw new \Exception(t('app', "Template with this type doesn't exists or can't be loaded"));
        }
    }

    /**
     * Get list of types
     *
     * @param string $filter types separated by comma, example '1,2'
     *
     * @return array
     */
    public static function getTypesList($filter = '')
    {
        self::$templateTypesLabel = [
            TemplateTypeAd::TEMPLATE_TYPE             => t('app', 'Ad'),
            TemplateTypeInvoice::TEMPLATE_TYPE        => t('app', 'Invoice'),
            TemplateTypeAdmin::TEMPLATE_TYPE          => t('app', 'Admin'),
            TemplateTypeCustomer::TEMPLATE_TYPE       => t('app', 'Customer'),
            TemplateTypeAdConversation::TEMPLATE_TYPE => t('app', 'Ad Conversation'),
            TemplateTypeContact::TEMPLATE_TYPE        => t('app', 'Contact')
        ];

        $event = new Event();
        $event->params = [
            'templateTypesLabel' => self::$templateTypesLabel,
        ];
        app()->trigger('app.components.mailTemplate.templatesLabelsGathering', $event);

        // apply filter
        if ($filter) {
            $allowed = explode(',', $filter);

            return array_intersect_key($event->params['templateTypesLabel'], array_flip($allowed));
        }

        return $event->params['templateTypesLabel'];
    }

}