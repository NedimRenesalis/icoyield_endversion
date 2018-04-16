<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <div class="form-group">
        <?php $required = ($model->required == 'yes') ? 'required' : ''; ?>
        <div class="input-group no-icon">
            <input type="number" step="any" class="form-control" name="CategoryField[<?=(int)$model->field_id;?>]" value="<?=html_encode($value);?>" placeholder="<?=html_encode($model->label);?>" data-container="body"  data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?=html_encode($model->help_text);?>" <?=$required;?> />
            <span class="input-group-addon custom-unit"><?=html_encode($model->unit);?></span>
        </div>
    </div>
</div>