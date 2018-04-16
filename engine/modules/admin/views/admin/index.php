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

        <p class="login-box-msg"><?= t('app', 'Sign in to start your session');?></p>

        <?php $form = ActiveForm::begin([
            'id'        => 'login-form',
            'method'    => 'post',
        ]); ?>

        <?= $form->field($model, 'email', [
            'template' => '{input} <span class="glyphicon glyphicon-envelope form-control-feedback"></span> {error}',
            'options'  => [
                'class' => 'form-group has-feedback'
            ]
        ])->textInput(['placeholder' => 'Email'])->label(false); ?>

        <?= $form->field($model, 'password', [
            'template' => '{input} <span class="glyphicon glyphicon-lock form-control-feedback"></span> {error}',
            'options'  => [
                'class' => 'form-group has-feedback'
            ]
        ])->passwordInput(['placeholder' => 'Password'])->label(false); ?>

        <?php if($captchaSiteKey = options()->get('app.settings.common.captchaSiteKey', '')){
                echo $form->field($model, 'reCaptcha')->widget(
                    \himiklab\yii2\recaptcha\ReCaptcha::className(),
                    ['siteKey' => html_encode($captchaSiteKey)]
                )->label(false);
         } ?>

        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'rememberMe', [
                    'options' => [
                        'class' => 'checkbox icheck'
                    ],
                ])->checkbox(); ?>
            </div>
            <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat"><?= t('app', 'Sign In');?></button>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

        <a href="<?= url(['admin/forgot']);?>"><?= t('app', 'I forgot my password');?></a>

    </div>
</div>