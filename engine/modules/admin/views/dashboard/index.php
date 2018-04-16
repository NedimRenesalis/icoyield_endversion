<?php

use \app\modules\admin\components\InfoBoxWidget;
use \app\modules\admin\components\TableBoxWidget;
use \app\modules\admin\components\LatestAdsWidget;
use dosamigos\highcharts\HighCharts;

$this->title = t('app', 'Dashboard');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= InfoBoxWidget::widget([
        'boxType'   => InfoBoxWidget::BOX_LIFETIME_SALES,
        'title'     => t('app', 'Lifetime Sales') . ' - ' . options()->get('app.settings.common.siteCurrency', 'usd'),
        'icon'      => 'fa-money'
        ]); ?>
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= InfoBoxWidget::widget([
            'boxType'   => InfoBoxWidget::BOX_PAID_ADS,
            'title'     => t('app', 'Paid Ads'),
            'icon'      => 'fa-bar-chart'
        ]); ?>
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= InfoBoxWidget::widget([
            'boxType'   => InfoBoxWidget::BOX_FREE_ADS,
            'title'     => t('app', 'Free Ads'),
            'icon'      => 'fa-check'
        ]); ?>
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= InfoBoxWidget::widget([
            'boxType'   => InfoBoxWidget::BOX_ORDERS,
            'title'     => t('app', 'Orders'),
            'icon'      => 'fa-shopping-cart'
        ]); ?>
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= InfoBoxWidget::widget([
            'boxType'   => InfoBoxWidget::BOX_CUSTOMERS,
            'title'     => t('app', 'Customers'),
            'icon'      => 'fa-users'
        ]); ?>
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= InfoBoxWidget::widget([
            'boxType'   => InfoBoxWidget::BOX_FAVORITES,
            'title'     => t('app', 'Global favorite ads'),
            'icon'      => 'fa-heart'
        ]); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="box box-primary" style="height: 455px">
            <div class="box-header with-border">
                <h3 class="box-title"><?=t('app','Business Chart');?></h3>
            </div>

            <div class="box-body">
                <?php $sales = \app\models\Order::getLastMonthsSalesAsArray();?>
                <?= HighCharts::widget([
                    'clientOptions' => [
                        'title' => [
                            'text' => t('app', 'Revenue in ') . options()->get('app.settings.common.siteCurrency', 'usd')
                        ],
                        'xAxis' => [
                            'categories' => $sales['months']
                        ],
                        'yAxis' => [
                            'title' => [
                                'text' => t('app', 'amount')
                            ]
                        ],
                        'series' => [
                            ['name' => options()->get('app.settings.common.siteCurrency', 'usd'), 'data' => $sales['sales']],
                        ]
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <?= LatestAdsWidget::widget([
            'title'     => t('app', 'Recently Added Ads'),
            'allLink'      => url(['/admin/listings'])
        ]); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <?= TableBoxWidget::widget([
            'tableType'     => TableBoxWidget::TABLE_ORDERS,
            'title'         => t('app', 'Last Orders'),
            'allLink'       => url(['/admin/orders']),
            'columns'       => [
                    'order_id'          => t('app', 'ID'),
                    'order_title'       => t('app', 'Item'),
                    'first_name'        => t('app', 'First Name'),
                    'last_name'         => t('app', 'Last Name'),
                    'city'              => t('app', 'City'),
                    'status'            => t('app', 'Status'),
                    'total'             => t('app', 'Total'),
                    'created_at'        => t('app', 'Created'),
            ]
        ]); ?>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <?= TableBoxWidget::widget([
            'tableType'     => TableBoxWidget::TABLE_CUSTOMERS,
            'title'         => t('app', 'Last Customers'),
            'allLink'       => url(['/admin/customers']),
            'columns'       => [
                'customer_id'       => t('app', 'ID'),
                'first_name'        => t('app', 'First Name'),
                'last_name'         => t('app', 'Last Name'),
                'gender'            => t('app', 'Gender'),
                'source'            => t('app', 'Source'),
                'status'            => t('app', 'Status'),
                'created_at'        => t('app', 'Created'),
            ]
        ]); ?>
    </div>
</div>