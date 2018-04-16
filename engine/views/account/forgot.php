<?php
use yii\bootstrap\ActiveForm;
use app\assets\AccountAsset;

AccountAsset::register($this);
?>
<div class="forgot-password">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-lg-push-4 col-md-4 col-md-push-4 col-sm-6 col-sm-push-3 col-xs-12">
                <h1><?=t('app','Forgot password');?></h1>
                <?php $form = ActiveForm::begin([
                    'id'        => 'forgot-form',
                    'method'    => 'post',
                ]); ?>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <?= $form->field($model, 'email', [
                                    'template' => '{input} {error}',
                                ])->textInput([ 'placeholder' => 'Email', 'class' => 'form-control with-addon'])->label(false); ?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <a href="<?= url(['account/login']);?>" class="forgot"><i class="fa fa-angle-double-left" aria-hidden="true"></i> <?=t('app', 'Back to Sign In');?></a>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <button type="submit" class="btn-as pull-right" value="Search"><?=t('app', 'Reset');?></button>
                        </div>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>