<?php 
use app\models\CategoryFieldOption;
?>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <div class="form-group">
        <select id="ad-field-<?=(int)$model->field_id;?>" name="CategoryField[<?=(int)$model->field_id;?>]">
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