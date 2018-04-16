<?php
$user = app()->user->identity;
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <?php /* Sep 2017 - Disabled User profile in menu
        <div class="user-panel">
            <div class="pull-left image">
                <?php $avatar = (is_file(\Yii::getAlias('@webroot') . $user['avatar'])) ? $user['avatar'] : Yii::getAlias('@web/assets/admin/img/default.jpg'); ?>
                <img src="<?= $avatar; ?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= html_encode($user['first_name']) . ' ' . html_encode($user['last_name']); ?></p>
                <small><?= t('app', 'Member since'); ?> <?= date('M, Y', strtotime($user['created_at'])); ?></small>
            </div>
        </div>
        */ ?>

        <?php $menuItems = [
            ['label' => 'Support', 'icon' => 'question-circle-o', 'url' => 'https://www.easyads.io/kb', 'target' => '_blank'],
            ['label' => 'Dashboard', 'icon' => 'tachometer', 'url' => ['/admin/dashboard']],
            [
                'label' => 'Ads Management',
                'icon' => 'tags',
                'url' => '#',
                'items' => [
                    ['label' => 'All Ads', 'icon' => 'chevron-right', 'url' => ['/admin/listings']],
                    ['label' => 'Pending Ads', 'icon' => 'chevron-right', 'url' => ['/admin/listings/pendings']],
                    ['label' => 'Packages', 'icon' => 'chevron-right', 'url' => ['/admin/listings-packages']],
                    ['label' => 'Categories', 'icon' => 'chevron-right', 'url' => ['/admin/categories']],
                ]
            ],
            [
                'label' => 'Content',
                'icon' => 'file-text-o',
                'url' => '#',
                'items' => [
                    ['label' => 'Pages', 'icon' => 'chevron-right', 'url' => ['/admin/pages']],
                    ['label' => 'Contact Form', 'icon' => 'chevron-right', 'url' => ['/admin/contact']],
                ]
            ],
            [
                'label' => 'Customers',
                'icon' => 'users',
                'url' => '#',
                'items' => [
                    ['label' => 'Customers', 'icon' => 'chevron-right', 'url' => ['/admin/customers']],
                    ['label' => 'Stores', 'icon' => 'chevron-right', 'url' => ['/admin/customer-stores']],
                    ['label' => 'Messages', 'icon' => 'chevron-right', 'url' => ['/admin/conversations']],
                ]
            ],
            [
                'label' => 'Admins',
                'icon' => 'user-circle-o',
                'url' => '#',
                'items' => [
                    ['label' => 'Users', 'icon' => 'chevron-right', 'url' => ['/admin/users']],
                    ['label' => 'Groups', 'icon' => 'chevron-right', 'url' => ['/admin/groups']],
                ]
            ],
            [
                'label' => 'Settings',
                'icon' => 'cogs',
                'url' => '#',
                'items' => [
                    ['label' => 'General', 'icon' => 'chevron-right', 'url' => ['/admin/settings/index']],
                    ['label' => 'Gateways', 'icon' => 'chevron-right', 'url' => ['/admin/gateways']],
                    ['label' => 'Invoice', 'icon' => 'chevron-right', 'url' => ['/admin/settings/invoice']],
                    ['label' => 'Theme', 'icon' => 'chevron-right', 'url' => ['/admin/settings/theme']],
                    ['label' => 'Social', 'icon' => 'chevron-right', 'url' => ['/admin/settings/social']],
                    ['label' => 'License', 'icon' => 'chevron-right', 'url' => ['/admin/settings/license']]
                ],
            ],
            [
                'label' => 'Finance',
                'icon' => 'usd',
                'url' => '#',
                'items' => [
                    ['label' => 'Orders', 'icon' => 'chevron-right', 'url' => ['/admin/orders']],
                    ['label' => 'Transactions', 'icon' => 'chevron-right', 'url' => ['/admin/order-transactions']],
                    ['label' => 'Taxes', 'icon' => 'chevron-right', 'url' => ['/admin/taxes']],
                    ['label' => 'Invoices', 'icon' => 'chevron-right', 'url' => ['/admin/invoices']],

                ],
            ],
            [
                'label' => 'Localization',
                'icon' => 'globe',
                'url' => '#',
                'items' => [
                    ['label' => 'Currencies', 'icon' => 'chevron-right', 'url' => ['/admin/currencies']],
                    ['label' => 'Languages', 'icon' => 'chevron-right', 'url' => ['/admin/languages']],
                    ['label' => 'Countries', 'icon' => 'chevron-right', 'url' => ['/admin/countries']],
                    ['label' => 'Zones', 'icon' => 'chevron-right', 'url' => ['/admin/zones']],
                ]
            ],
            [
                'label' => 'Email System',
                'icon' => 'envelope',
                'url' => '#',
                'items' => [
                    ['label' => 'Templates', 'icon' => 'chevron-right', 'url' => ['/admin/mail-templates']],
                    ['label' => 'Accounts', 'icon' => 'chevron-right', 'url' => ['/admin/mail-accounts']],
                    ['label' => 'Queue', 'icon' => 'chevron-right', 'url' => ['/admin/mail-queue']],
                ]
            ],
            [
                'label' => 'Miscellaneous',
                'icon' => 'plug',
                'url' => '#',
                'items' => [
                    ['label' => 'Admin activity log', 'icon' => 'chevron-right', 'url' => ['/admin/admin-action-logs']],
                    ['label' => 'Cron Jobs', 'icon' => 'chevron-right', 'url' => ['/admin/cron']],
                ]
            ],
            ['label' => 'Extension Manager', 'icon' => 'plus-circle', 'url' => ['/admin/extensions']],
        ]; ?>

        <?php
        $event = new \app\yii\base\Event();
        $event->params = [
            'menu' => $menuItems,
            ];
            app()->trigger('app.admin.menu', $event);
        ?>

        <?= $menu = \app\modules\admin\components\MenuWidget::widget(
            [
                'options' => [
                    'class'         => 'sidebar-menu tree',
                    'data-widget'   => 'tree'
                ],
                'items' => $event->params['menu'],
            ]
        ); ?>
    </section>
</aside>
