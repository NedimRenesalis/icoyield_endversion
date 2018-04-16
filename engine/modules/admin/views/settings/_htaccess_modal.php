
<div>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo t('app', '.htaccess contents')?></h4>
    </div>
    <div class="modal-body">
        <div class="modal-message"></div>
        <?php echo \yii\helpers\Html::textArea('htaccess', $controller->getHtaccessContent(), array('rows' => 10, 'id' => 'htaccess', 'class' => 'form-control'));?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo t('app', 'Close');?></button>
        <button type="button" class="btn btn-primary btn-flat btn-write-htaccess" data-remote="<?=url(['settings/write-htaccess']);?>"><?php echo t('app', 'Write htaccess');?></button>
    </div>
</div>
