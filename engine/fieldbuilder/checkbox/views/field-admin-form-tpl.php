
<div class="col-lg-12">
    <div class="form-group">
        <?php
        $checked = (!empty($value)) ? 'checked="checked"' : '';
        ?>
        <input type="hidden" name="CategoryField[<?=(int)$model->field_id;?>]" value="0">
        <input type="checkbox" class="form-check-input" id="checkbox-field-[<?=(int)$model->field_id;?>]" name="CategoryField[<?=(int)$model->field_id;?>]" <?=$checked;?> value="1">
        <label class="form-check-label" for="checkbox-field-[<?=(int)$model->field_id;?>]" data-container="body"  data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?=html_encode($model->help_text);?>"><?=html_encode($model->label);?></label>
    </div>
</div>



