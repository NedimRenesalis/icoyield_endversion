<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <div class="form-group">
        <?php $required = ($model->required == 'yes') ? 'required' : ''; ?>
        <input type="url" class="form-control" name="CategoryField[<?=(int)$model->field_id;?>]" value="<?=html_encode($value);?>" placeholder="<?=html_encode($model->label);?>" <?=$required;?> data-container="body" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?=html_encode($model->help_text);?>" />
    </div>
</div>