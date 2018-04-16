<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <div class="form-group">
        <?php
        $checked = (!empty($value)) ? 'checked="checked"' : '';
        ?>
        <input type="hidden" name="CategoryField[<?=(int)$model->field_id;?>]" value="0">
        <input type="checkbox" id="checkbox-field-[<?=(int)$model->field_id;?>]" name="CategoryField[<?=(int)$model->field_id;?>]" <?=$checked;?> value="1">
        <label for="checkbox-field-[<?=(int)$model->field_id;?>]"><?=html_encode($model->label);?></label>
    </div>
</div>