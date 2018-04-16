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

namespace app\fieldbuilder;

use yii\base\Widget;

class Type extends Widget
{
    /**
     * @var array
     */
    public $params = [];

    /**
     * @var
     */
    public $form;

    /**
     * @var
     */
    public $category;

    /**
     * @var
     */
    public $field;

    /**
     * @return int
     */
    final public function getIndex()
    {
        // use unique value because counter++ is causing
        // issues when importing inherit parent fields
        return uniqid();
    }
}