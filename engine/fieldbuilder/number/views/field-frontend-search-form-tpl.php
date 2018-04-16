<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <div class="input-group no-icon">
                    <input type="number" step="any" class="form-control" name="CategoryField[<?=(int)$model->field_id;?>][min]" value="<?=$minValue;?>" placeholder="<?=html_encode($model->label) . ' ' . t('app', 'Min'); ?>" />
                    <span class="input-group-addon custom-unit"><?=html_encode($model->unit);?></span>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <div class="input-group no-icon">
                    <input type="number" step="any"  class="form-control" name="CategoryField[<?=(int)$model->field_id;?>][max]" value="<?=$maxValue;?>" placeholder="<?=html_encode($model->label) . ' ' . t('app', 'Max'); ?>" />
                    <span class="input-group-addon custom-unit"><?=html_encode($model->unit);?></span>
                </div>
            </div>
        </div>
    </div>
</div>