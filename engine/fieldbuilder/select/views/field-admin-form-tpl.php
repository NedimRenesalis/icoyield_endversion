<?php
use app\models\CategoryFieldOption;
?>
<div class="col-lg-6">
    <div class="form-group">
        <label class="control-label" ><?=html_encode($model->label); ?></label>
        <?php $required = ($model->required == 'yes') ? 'required' : ''; ?>
            <select id="ad-field-<?=(int)$model->field_id;?>" class="form-control" name="CategoryField[<?=(int)$model->field_id;?>]" data-container="body"  data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?=html_encode($model->help_text);?>" <?=$required;?>>
                <option value=""><?=html_encode($model->label);?></option>
                <?php
                $options = CategoryFieldOption::find()->where(['field_id'=>$model->field_id])->all();
                foreach ($options as $option){
                    $selected = ($option->value == $value) ? ' selected' : '';
                    ?>
                    <option value="<?=html_encode($option->value);?>" <?=$selected;?>><?=html_encode($option->name);?></option>
                <?php } ?>
            </select>
    </div>
</div>
