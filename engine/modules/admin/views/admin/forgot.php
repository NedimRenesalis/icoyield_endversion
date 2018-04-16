<?php

use yii\bootstrap\ActiveForm;

?>

<div class="login-box">
    <div class="login-logo">
        <a href="<?=\yii\helpers\Url::home();?>">
            <img width="300" src="<?=options()->get('app.settings.theme.siteLogo', \Yii::getAlias('@web/assets/site/img/logo.png'));?>" />
        </a>
    </div>

    <div id="notify-container">
        <?= notify()->show();?>
    </div>

    <div class="login-box-body">

        <p class="login-box-msg"><?= t('app', 'Enter your email to reset your password');?></p>

        <?php $form = ActiveForm::begin([
            'id'        => 'forgot-form',
            'method'    => 'post',
        ]); ?>

        <?= $form->field($model, 'email', [
            'template' => '{input} <span class="glyphicon glyphicon-envelope form-control-feedback"></span> {error}',
            'options'  => [
                'class' => 'form-group has-feedback'
            ]
        ])->textInput(['placeholder' => 'Email'])->label(false); ?>

        <div class="row">
            <div class="col-xs-6"></div>
            <div class="col-xs-6">
                <button type="submit" class="btn btn-primary btn-block btn-flat"><?= t('app', 'Reset password');?></button>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

        <a href="<?= url(['admin/index']);?>"><?= t('app', 'Back to login');?></a>

    </div>
</div>