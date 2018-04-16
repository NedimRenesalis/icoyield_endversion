<div class="col-lg-6">
    <div class="form-group">
        <label class="control-label" ><?=html_encode($model->label); ?></label>
        <?php $required = ($model->required == 'yes') ? 'required' : ''; ?>
        <div class="input-group no-icon">
            <input type="number" step="any" class="form-control" name="CategoryField[<?=(int)$model->field_id;?>]" value="<?=html_encode($value);?>" placeholder="<?=html_encode($model->label);?>" data-container="body"  data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?=html_encode($model->help_text);?>" <?=$required;?> />
            <span class="input-group-addon custom-unit"><?=html_encode($model->unit);?></span>
        </div>
    </div>
</div>