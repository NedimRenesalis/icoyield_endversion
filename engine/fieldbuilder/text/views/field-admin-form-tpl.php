<div class="col-lg-6">
    <div class="form-group">
        <label class="control-label" ><?=html_encode($model->label); ?></label>
        <?php $required = ($model->required == 'yes') ? 'required' : ''; ?>
        <input type="text" class="form-control" name="CategoryField[<?=(int)$model->field_id;?>]" value="<?=html_encode($value);?>" placeholder="<?=html_encode($model->label);?>" <?=$required;?> data-container="body" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?=html_encode($model->help_text);?>" />
    </div>
</div>