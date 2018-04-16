<?php

use \yii\helpers\Html;

?>
<li class="title">
    <div class="truncate-ellipsis">
        <?= Html::a(
            html_encode(strtoupper($model->listing->title)),
            ['conversation/reply', 'conversation_uid' => $model->conversation_uid],
            ['data-pjax' => 0]
        );
        ?>
    </div>
</li>
<li class="date"><?= app()->formatter->asDate($model->created_at); ?></li>
<li class="actions icons">
    <?php if ($isArchivedList) { ?>
        <?= Html::a(
            '<i class="fa fa-trash-o" aria-hidden="true"></i><span>' . t('app', 'Delete') . '</span>',
            ['#'],
            [
                'class'                => 'btn-as danger-action delete-conversation-action',
                'data-url'             => url(['conversation/delete']),
                'data-conversation-uid' => $model->conversation_uid,
                'data-pjax'            => 0
            ]
        );
        ?>
    <?php } else { ?>
        <?= Html::a(
            '<i class="fa fa-archive" aria-hidden="true"></i><span>' . t('app', 'Archive') . '</span>',
            ['#'],
            [
                'class'                => 'btn-as transparent archive-conversation-action',
                'data-url'             => url(['conversation/archive']),
                'data-conversation-uid' => $model->conversation_uid,
                'data-pjax'            => 0
            ]
        );
        ?>
    <?php } ?>
    <?= Html::a(
        '<i class="fa fa-comments" aria-hidden="true"></i><span>' . t('app', 'Reply') . '</span>',
        ['conversation/reply', 'conversation_uid' => $model->conversation_uid],
        [
            'class'     => 'btn-as',
            'data-pjax' => 0
        ]
    );
    ?>
</li>