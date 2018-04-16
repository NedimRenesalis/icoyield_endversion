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
 * This is the ActiveQuery class for [[Order]].
 *
 * @see Order
 */
class OrderQuery extends \app\models\auto\OrderQuery
{
    /**
     * Filter orders by status paid
     *
     * @inheritdoc
     * @return Order[]|array
     */
    public function paid()
    {
        return $this->andWhere(['status' => Order::STATUS_PAID]);
    }
}
