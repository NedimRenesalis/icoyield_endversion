<?php
use yii\bootstrap\ActiveForm;
use app\assets\AccountAsset;
use yii\authclient\widgets\AuthChoice;

AccountAsset::register($this);
?>
<div class="sign-in">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-lg-push-4 col-md-4 col-md-push-4 col-sm-6 col-sm-push-3 col-xs-12">
                <h1><?= t('app','Sign In');?></h1>
                <?php $form = ActiveForm::begin([
                    'id'        => 'signin-form',
                    'method'    => 'post',
                ]); ?>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?= $form->field($model, 'email', [
                                    'template'      => '{input} {error}',
                            ])->textInput([ 'placeholder' => t('app', 'Email'), 'class' => 'form-control with-addon'])->label(false); ?>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?= $form->field($model, 'password', [
                                'template' => '{input} {error}',
                            ])->passwordInput(['placeholder' => t('app','Password'), 'class' => 'form-control with-addon'])->label(false); ?>
                        </div>
                        <?php if($captchaSiteKey = options()->get('app.settings.common.captchaSiteKey', '')){ ?>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <?= $form->field($model, 'reCaptcha')->widget(
                                    \himiklab\yii2\recaptcha\ReCaptcha::className(),
                                    ['siteKey' => html_encode($captchaSiteKey)]
                                )->label(false); ?>
                            </div>
                        <?php } ?>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <?= $form->field($model, 'rememberMe', [
                                'options' => [
                                    'class' => 'checkbox icheck'
                                ],
                            ])->checkbox(['template' => '{input} {label}'],['value'=>true]); ?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <a href="<?= url(['account/forgot']);?>" class="forgot pull-right"><?= t('app','Forgot password?');?></a>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="a-center">
                                <button type="submit" class="btn-as" value="Search"><?=t('app','Log in');?></button>
                            </div>
                        </div>
                        <?php if (options()->get('app.settings.common.siteFacebookId', '') && options()->get('app.settings.common.siteFacebookSecret', '')) { ?>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="separator-text">
                                <span><?=t('app','Or sign in with');?></span>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="a-center">
                                <?php
                                    $authAuthChoice = AuthChoice::begin([
                                        'baseAuthUrl' => ['account/loginfb']
                                    ]);
                                    foreach ($authAuthChoice->getClients() as $client) {
                                        if($client->getName() == 'facebook') {
                                            echo $authAuthChoice->clientLink($client,'facebook',['class'=>'btn-as facebook']);
                                        }
                                    }
                                    AuthChoice::end();
                                ?>
                            </div>
                        </div>
                        <?php } ?>
                        <?php if (options()->get('app.settings.common.siteGoogleId', '') && options()->get('app.settings.common.siteGoogleSecret', '')) { ?>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="a-center">
                                    <?php
                                    $authAuthChoice = AuthChoice::begin([
                                        'baseAuthUrl' => ['account/loginGoogle']
                                    ]);
                                    foreach ($authAuthChoice->getClients() as $client) {
                                        if($client->getName() == 'google') {
                                            echo $authAuthChoice->clientLink($client,'google',['class'=>'btn-as googleplus']);
                                        }
                                    }
                                    AuthChoice::end();
                                    ?>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (options()->get('app.settings.common.siteLinkedInId', '') && options()->get('app.settings.common.siteLinkedInSecret', '')) { ?>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="a-center">
                                    <?php
                                    $authAuthChoice = AuthChoice::begin([
                                        'baseAuthUrl' => ['account/loginLinkedIn']
                                    ]);
                                    foreach ($authAuthChoice->getClients() as $client) {
                                        if($client->getName() == 'linkedin') {
                                            echo $authAuthChoice->clientLink($client,'linkedin',['class'=>'btn-as linkedin']);
                                        }
                                    }
                                    AuthChoice::end();
                                    ?>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (options()->get('app.settings.common.siteTwitterId', '') && options()->get('app.settings.common.siteTwitterSecret', '')) { ?>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="a-center">
                                    <?php
                                    $authAuthChoice = AuthChoice::begin([
                                        'baseAuthUrl' => ['account/loginTwitter'],
                                    ]);
                                    foreach ($authAuthChoice->getClients() as $client) {
                                        if ($client->getName() == 'twitter') {
                                            echo $authAuthChoice->clientLink($client, 'twitter', ['class' => 'btn-as twitter']);
                                        }
                                    }
                                    AuthChoice::end();
                                    ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>