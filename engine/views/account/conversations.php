<?php

use app\assets\AccountAsset;

AccountAsset::register($this);

echo $this->render('_navigation.php', []);
?>

<div class="inbox">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs">
        <li class="active"><a href="#active-conversations" role="tab" data-toggle="tab"><?=t('app','Active');?></a></li>
        <li><a href="#archived-conversations" role="tab" data-toggle="tab"><?=t('app','Archived');?></a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="active-conversations">
            <?= $this->render('_conversations_list', [
                'conversationsProvider' => $activeConversationsProvider,
            ]); ?>
        </div>
        <div class="tab-pane" id="archived-conversations">
            <?= $this->render('_conversations_list', [
                'conversationsProvider' => $archivedConversationsProvider,
                'isArchivedList'        => true,
            ]); ?>
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
</div>
