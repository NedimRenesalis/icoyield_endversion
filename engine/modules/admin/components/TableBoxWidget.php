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

namespace app\modules\admin\components;

use app\models\Customer;
use app\models\Order;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\db\Expression;

/**
 * Class TableBoxWidget
 * @package app\modules\admin\components
 */
class TableBoxWidget extends Widget
{
    /**
     * types of available tables
     */
    const TABLE_ORDERS        = 1;
    const TABLE_CUSTOMERS     = 2;

    /**
     * Constants
     */
    const TABLE_LIMIT = 5;

    /**
     * @var table type
     */
    public $tableType;
    /**
     * @var string title of table
     */
    public $title;
    /**
     * @var
     */
    public $allLink;
    /**
     * @var
     */
    public $columns;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->allLink === null) {
            $this->allLink = '#';
        }

        if ($this->columns === null) {
            throw new InvalidConfigException('"' . $this->tableType . '" columns are required');
        }

        if (!in_array($this->tableType, [
            self::TABLE_ORDERS,
            self::TABLE_CUSTOMERS,
        ])) {
            throw new InvalidConfigException('"' . $this->tableType . '" table type is not allowed.');
        }
    }

    /**
     * @return string
     */
    public function run()
    {
        if ($this->isOrdersTable()) {
            $query = Order::find()
                ->orderBy(['order_id' => SORT_DESC])
                ->limit(self::TABLE_LIMIT)
                ->all();
        } else if ($this->isCustomersTable()) {
            $query = Customer::find()
                ->orderBy(['customer_id' => SORT_DESC])
                ->limit(self::TABLE_LIMIT)
                ->all();
        }

        return $this->render('table-box/table-box', [
            'data'      => $query,
            'title'     => $this->title,
            'link'      => $this->allLink,
            'columns'   => $this->columns
        ]);
    }

    /**
     * Check whether the table is orders
     *
     * @return bool
     */
    public function isOrdersTable()
    {
        return self::TABLE_ORDERS == $this->tableType;
    }

    /**
     * Check whether the table is customers
     *
     * @return bool
     */
    public function isCustomersTable()
    {
        return self::TABLE_CUSTOMERS == $this->tableType;
    }
}