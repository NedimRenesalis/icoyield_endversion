<?php

return [
    'id'=> 'admin',
    'viewPath' => '@app/modules/admin/views',
    'layout' => '@app/modules/admin/views/layouts/main',
    'components' => [
        'trackUserAction' => [
            'class' => 'app\modules\admin\components\TrackUserActionComponent'
        ],
        'notify' => [
            'class' => '\twisted1919\notify\Notify',
        ],
    ]
];

?>