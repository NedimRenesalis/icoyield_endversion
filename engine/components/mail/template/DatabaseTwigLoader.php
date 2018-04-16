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

/**
 * Class DatabaseTwigLoader
 * @package app\components\twig
 */
class DatabaseTwigLoader implements \Twig_LoaderInterface, \Twig_ExistsLoaderInterface, \Twig_SourceContextLoaderInterface
{
    /**
     * @inheritdoc
     */
    public function getSource($name)
    {
        $template = $this->findTemplateBySlug($name);

        return $template;
    }

    /**
     * @inheritdoc
     */
    public function getSourceContext($name)
    {
        $template = $this->findTemplateBySlug($name);

        return new \Twig_Source($template->content, $name);
    }

    /**
     * @inheritdoc
     */
    public function exists($name)
    {
        return MailTemplate::find()->where(['slug' => $name])->exists();
    }

    /**
     * @inheritdoc
     */
    public function getCacheKey($name)
    {
        return $name;
    }

    /**
     * @inheritdoc
     */
    public function isFresh($name, $time)
    {
        $template = $this->findTemplateBySlug($name);

        return app()->formatter->asTimestamp($template->updated_at) <= $time;
    }

    /**
     * Find template of email by unique slug
     *
     * @param $slug
     *
     * @return MailTemplate
     * @throws \Twig_Error_Loader
     */
    protected function findTemplateBySlug($slug)
    {
        if (($template = MailTemplate::findOne(['slug' => $slug])) !== null) {
            return $template;
        } else {
            throw new \Twig_Error_Loader(sprintf('Template "%s" does not exist.', $slug));
        }
    }
}