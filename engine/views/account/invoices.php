<?php
use app\assets\AccountAsset;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use app\helpers\SvgHelper;

AccountAsset::register($this);

echo $this->render('_navigation.php', []);
?>
<div class="container">
    <div class="row">
        <?php Pjax::begin([
            'enablePushState' => true,
        ]); ?>
        <?= ListView::widget([
            'id'           => 'invoices',
            'dataProvider' => $invoicesProvider,
            'itemView'     => '_invoice_item',
            'layout'       => '
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <ul class="list list-invoice">
                            <li class="list-head">
                                <ul>
                                    <li class="number">' . t('app','Invoice number') . '</li>
                                    <li class="date">' . t('app','date') . '</li>
                                    <li class="total">' . t('app','Total') . '</li>
                                    <li class="actions"></li>
                                </ul>
                            </li>
                            <li class="list-body">
                                {items}
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="pagination-custom">
                            <div class="row">
                                {pager}
                            </div>
                        </div>
                    </div>
                ',
            'itemOptions'  => [
                'tag' => 'ul'
            ],
            'emptyText' => '
                <li class="list-head">
                    <ul>
                        <li class="number">' . t('app', 'Invoices') . '</li>
                        <li class="date"></li>
                        <li class="total"></li>
                        <li class="actions"></li>
                    </ul>
                </li>
                <li class="list-body">
                    <ul>
                        <li class="empty">' . t('app', 'No results found') . '</li>
                    </ul>
                </li>',
            'emptyTextOptions' => ['tag' => 'ul', 'class' => 'list list-invoice'],
            'pager'        => [
                'class'         => 'app\widgets\CustomLinkPager',
                'prevPageLabel' => SvgHelper::getByName('arrow-left'),
                'nextPageLabel' => SvgHelper::getByName('arrow-right')
            ]
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>