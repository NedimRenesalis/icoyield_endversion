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

use app\models\MailTemplate;
use Yii;
use yii\base\Component;

/**
 *
 * Configuration of component
 *
 * ~~~
 *    'components' => [
 *        ...
 *        'twigTemplate' => [
 *            'class' => 'app\components\mail\template\MailTemplateComponent',
 *        ],
 *        ...
 *    ],
 * ~~~
 * Usage
 *      app()->twigTemplate->generateMessage($mailTemplate, $data);
 *
 *      $mailTemplate is object that store details of template that should be generated
 *      $data is array of template variables that depends on type of template
 *
 * @package app\components
 */
class MailTemplateComponent extends Component
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $loader = new DatabaseTwigLoader();
        $this->twig = new \Twig_Environment($loader, [
            'cache'       => Yii::getAlias('@webroot') . '/assets/twig/cache',
            'auto_reload' => true,
        ]);
    }

    /**
     * Load from db and generate template
     *
     * @param MailTemplate $mailTemplate
     * @param              $data
     * @return array
     */
    public function generateMessage(MailTemplate $mailTemplate, array $data)
    {
        $template = $this->twig->load($mailTemplate->slug);
        $templateType = TemplateType::create($mailTemplate->template_type, $data);
        $data = $templateType->populate();

        return [
            'message'   => $template->render($data),
            'recipient' => $templateType->getRecipient()
        ];
    }
}