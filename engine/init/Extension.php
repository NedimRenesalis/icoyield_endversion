<?php

/**
 *
 * @package    EasyAds
 * @author     CodinBit <contact@codinbit.com>
 * @link       https://www.easyads.io
 * @copyright  2017 EasyAds (https://www.easyads.io)
 * @license    https://www.easyads.io
 * @since      1.3
 */

namespace app\init;

/**
 * Base class for each extension
 *
 * @package app\init\Extension
 */
abstract class Extension
{
    /**
     * Name of extension to display in app
     *
     * @var string
     */
    public $name = '';

    /**
     * Name of author of extension to display in app
     *
     * @var string
     */
    public $author = '';

    /**
     * Version of extension to display in app
     *
     * @var string
     */
    public $version = '';

    /**
     * Description of extension to display in app
     *
     * @var string
     */
    public $description = '';

    /**
     * Type of extension
     *
     * @var string
     */
    public $type = '';

    abstract public function run();
}