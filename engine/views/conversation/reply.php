<?php

use yii\bootstrap\ActiveForm;
use app\assets\AccountAsset;

AccountAsset::register($this);

/* @var $this yii\web\View */

echo $this->render('//account/_navigation.php', []);
?>
<section class="conversation-reply">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 center-block">
                <div class="row block-heading">
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                        <div class="truncate-ellipsis">
                            <a class="title" href="<?=url(['/listing/index/' . $conversation->listing->slug]);?>" target="_blank"><?= $conversation->listing->title ?></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="actions">
                            <?php if ($conversation->isArchived()) { ?>
                                <?= \yii\helpers\Html::a(
                                    '<i class="fa fa-trash-o" aria-hidden="true"></i><span>' . t('app', 'Delete') . '</span>',
                                    ['#'],
                                    [
                                        'class'                => 'btn-as danger-action delete-conversation-action',
                                        'data-url'             => url(['conversation/delete']),
                                        'data-conversation-uid' => $conversation->conversation_uid
                                    ]
                                );
                                ?>
                            <?php } else { ?>
                                <?= \yii\helpers\Html::a(
                                    '<i class="fa fa-archive" aria-hidden="true"></i><span>' . t('app', 'Archive') . '</span>',
                                    ['#'],
                                    [
                                        'class'                => 'btn-as archive-conversation-action',
                                        'data-url'             => url(['conversation/archive']),
                                        'data-conversation-uid' => $conversation->conversation_uid
                                    ]
                                );
                                ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php foreach ($conversation->conversationMessages as $message) { ?>
            <?php if ($message->isAuthor(app()->customer->id)) { ?>
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 center-block">
                        <div class="talk-bubble tri-right border btm-right-in">
                            <div class="talktext">
                                <?= nl2br(html_purify(preg_replace("/[\r\n]+/", "\n", $message->message))); ?>
                            </div>
                        </div>
                        <ul class="info-bar pull-right">
                            <li><i class="fa fa-clock-o" aria-hidden="true"></i> <?= app()->formatter->asDatetime($message->created_at);?></li>
                        </ul>
                    </div>
                </div>
            <?php } else { ?>
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 center-block">
                        <div class="talk-bubble tri-right border btm-left-in">
                            <div class="talktext">
                                <?= nl2br(html_purify(preg_replace("/[\r\n]+/", "\n", $message->message))); ?>
                            </div>
                        </div>
                        <ul class="info-bar">
                            <li><i class="fa fa-user-o" aria-hidden="true"></i> <?= $message->author->fullName ?></li>
                            <li><i class="fa fa-clock-o" aria-hidden="true"></i> <?= app()->formatter->asDatetime($message->created_at);?></li>
                            <?php if ($conversation->isCustomerSeller(app()->customer->id)) { ?>
                                <li><?= \yii\helpers\Html::a(
                                        $conversation->isBuyerBlocked() ? '<i class="fa fa-unlock" aria-hidden="true"></i><span>' . t('app', ' Unblock user') . '</span>' : '<i class="fa fa-lock" aria-hidden="true"></i><span>' . t('app', ' Block user') . '</span>',
                                        ['#'],
                                        [
                                            'class'                => $conversation->isBuyerBlocked() ? 'unblock-buyer-action' : 'block-buyer-action',
                                            'data-url'             => url(['conversation/block-unblock-buyer']),
                                            'data-conversation-uid' => $conversation->conversation_uid
                                        ]
                                    );
                                    ?>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
        <?php if ($conversation->isCustomerSeller(app()->customer->id) || !$conversation->isBuyerBlocked()) { ?>
            <div class="row block">
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 center-block">
                    <?php $form = ActiveForm::begin([
                        'id' => 'conversation-reply-form'
                    ]); ?>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?= $form->field($conversationMessage, 'message', [
                                'template' => '{input} {error}',
                            ])->textarea(['placeholder' => t('app', 'Message'), 'class' => 'form-control'])->label(false); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <button type="submit" class="btn-as" value="Submit"><?= t('app', 'Submit'); ?></button>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        <?php } else { ?>
            <div class="row block">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    <section id="notify-container">
                        <div class="alert alert-block alert-warning"><?= t('app', "Sorry, but you can't write messages anymore. Conversation was blocked."); ?></div>
                    </section>
                </div>
            </div>
        <?php } ?>
    </div>
</section>

<div class="modal fade" id="modal-post-buyer-block" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-notice" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title"><?=t('app', 'Notice');?></h1>
                <a href="javascript:;" class="x-close" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></a>
            </div>
            <div class="modal-body">
                <p><?=t('app','Are you sure you want to block this buyer?');?></p>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <button type="button" class="btn-as block-buyer" data-dismiss="modal"><?=t('app', 'Block');?></button>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <button type="button" class="btn-as black pull-right" data-dismiss="modal"><?=t('app', 'Close');?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-post-buyer-unblock" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-notice" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title"><?=t('app', 'Notice');?></h1>
                <a href="javascript:;" class="x-close" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></a>
            </div>
            <div class="modal-body">
                <p><?=t('app','Are you sure you want to unblock this buyer?');?></p>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <button type="button" class="btn-as unblock-buyer" data-dismiss="modal"><?=t('app', 'Unblock');?></button>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <button type="button" class="btn-as black pull-right" data-dismiss="modal"><?=t('app', 'Close');?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-post-conversation-delete" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-notice" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title"><?=t('app', 'Notice');?></h1>
                <a href="javascript:;" class="x-close" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></a>
            </div>
            <div class="modal-body">
                <p><?=t('app','Are you sure you want to delete this conversation?');?></p>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <button type="button" class="btn-as delete-conversation" data-dismiss="modal"><?=t('app', 'Delete');?></button>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <button type="button" class="btn-as black pull-right" data-dismiss="modal"><?=t('app', 'Close');?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-post-conversation-archive" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-notice" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title"><?=t('app', 'Notice');?></h1>
                <a href="javascript:;" class="x-close" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></a>
            </div>
            <div class="modal-body">
                <p><?=t('app','Are you sure you want to archive this conversation?');?></p>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <button type="button" class="btn-as archive-conversation" data-dismiss="modal"><?=t('app', 'Archive');?></button>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <button type="button" class="btn-as black pull-right" data-dismiss="modal"><?=t('app', 'Close');?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
