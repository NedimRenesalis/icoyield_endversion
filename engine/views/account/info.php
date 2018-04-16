<?php
use app\assets\AccountAsset;
use yii\bootstrap\ActiveForm;
use app\helpers\DateTimeHelper;

AccountAsset::register($this);

echo $this->render('_navigation.php', []);
?>
<section class="my-account">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="block">
                    <div class="block-heading">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                <h1><?=t('app','Your ICOs main representative');?><span class="info"><?=t('app','Tell us more about your ICOs management');?>.</span></h1>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <a href="#about-block" data-toggle="collapse" class="btn-trigger-collapse collapsed">
                                    <span class="closed info-title"><?= t('app', 'Open')?></span>
                                    <span class="info-title"><?= t('app', 'Close')?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="block-body collapse" id="about-block">
                            <?php $form = ActiveForm::begin([
                                'id'        => 'form-change-about',
                                'method'    => 'post',
                                'action'    => ['account/update-info-about'],
                                'enableAjaxValidation' => false,
                            ]); ?>
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                    <?= $form->field($modelAbout, 'first_name', [
                                            'template'      => '{input} {error}',
                                    ])->textInput([ 'placeholder' => t('app','First Name'), 'class' => ''])->label(false); ?>
                                    </div>
                                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                    <?= $form->field($modelAbout, 'last_name', [
                                            'template'      => '{input} {error}',
                                    ])->textInput([ 'placeholder' => t('app','Last Name'), 'class' => ''])->label(false); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <?= $form->field($modelAbout, 'phone', [
                                            'template'      => '{input} {error}',
                                    ])->textInput([ 'placeholder' => t('app','Phone'), 'class' => ''])->label(false); ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <?= $form->field($modelAbout, 'gender', [
                                            'template'      => '{input} {error}',
                                    ])->dropDownList([ 'M' => t('app','Male'), 'F' => t('app','Female'), ],['prompt' => t('app','Gender')])->label(false); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <?= $form->field($modelAbout, 'birthdayDay', [
                                            'template'      => '{input} {error}',
                                    ])->dropDownList(DateTimeHelper::getStaticMonthDays(),['prompt' => t('app','Day')])->label(false);
                                    ?>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-2 col-xs-12">
                                    <?= $form->field($modelAbout, 'birthdayMonth', [
                                            'template'      => '{input} {error}',
                                    ])->dropDownList(DateTimeHelper::getStaticMonths(),['prompt' => t('app','Month')])->label(false);
                                    ?>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-2 col-xs-12">
                                    <?= $form->field($modelAbout, 'birthdayYear', [
                                                'template'      => '{input} {error}',
                                        ])->dropDownList(DateTimeHelper::getStaticBirthdayYears(),['prompt' => t('app','Year')])->label(false);
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <button type="submit" id="submit-account-info-about" class="btn-as" value="Submit"><?=t('app','Submit');?></button>
                                </div>
                            </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
                <?php if (options()->get('app.settings.common.disableStore', 0) == 0) { ?>
                    <div class="block">
                        <div class="block-heading">
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <h1><?=t('app','Cockpit');?><span class="info"><?=t('app','Here you can update your companys information');?>.</span></h1>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <a href="#company-block" data-toggle="collapse" class="btn-trigger-collapse collapsed">
                                        <span class="closed info-title"><?= t('app', 'Open')?></span>
                                        <span class="info-title"><?= t('app', 'Close')?></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="block-body collapse" id="company-block">
                            <?php $form = ActiveForm::begin([
                                'id'        => 'form-change-company',
                                'method'    => 'post',
                                'action'    => ['account/update-company'],
                                'enableAjaxValidation' => false,
                            ]); ?>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <?= $form->field($modelAbout, 'group_id', [
                                        'template'      => '{input} {error}',
                                    ])->dropDownList([2 => t('app','Enable'), 1 => t('app','Disable')])->label(false);
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                               
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <?= $form->field($modelCompany, 'company_name', [
                                        'template'      => '{input} {error}',
                                    ])->textInput([ 'placeholder' => t('app','Company Name'), 'class' => ''])->label(false); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <?= $form->field($modelCompany, 'company_no', [
                                        'template'      => '{input} {error}',
                                    ])->textInput([ 'placeholder' => t('app','Company No'), 'class' => ''])->label(false); ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <?= $form->field($modelCompany, 'vat', [
                                        'template'      => '{input} {error}',
                                    ])->textInput([ 'placeholder' => t('app','VAT'), 'class' => ''])->label(false); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <button type="submit" id="submit-company-info" class="btn-as" value="Submit"><?=t('app','Submit');?></button>
                                </div>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                <?php } ?>
                <div class="block">
                    <div class="block-heading">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                <h1><?=t('app','Change Password');?><span class="info"><?=t('app','Choose a unique password to protect your account');?>.</span></h1>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <a href="#change-password-block" data-toggle="collapse" class="btn-trigger-collapse collapsed">
                                    <span class="closed info-title"><?= t('app', 'Open')?></span>
                                    <span class="info-title"><?= t('app', 'Close')?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="block-body collapse" id="change-password-block">
                        <?php $form = ActiveForm::begin([
                            'id'        => 'form-change-password',
                            'method'    => 'post',
                            'action'    => ['account/update-password'],
                            'enableAjaxValidation' => false,
                            'enableClientValidation' => true,
                        ]); ?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <p><?=t('app','Passwords are case-sensitive and must be at least 6 characters');?>.</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                    <?= $form->field($modelPassword, 'passwordCurrent', [
                                            'template'      => '{input} {error}',
                                    ])->passwordInput([ 'placeholder' => t('app','Enter current password'), 'class' => ''])->label(false); ?>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                    <?= $form->field($modelPassword, 'password', [
                                            'template'      => '{input} {error}',
                                    ])->passwordInput([ 'placeholder' => t('app','Enter new password'), 'class' => ''])->label(false); ?>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                    <?= $form->field($modelPassword, 'passwordConfirm', [
                                            'template'      => '{input} {error}',
                                    ])->passwordInput([ 'placeholder' => t('app','Reenter new password'), 'class' => ''])->label(false); ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <button type="submit" class="btn-as" value="Search"><?=t('app','Submit');?></button>
                                </div>
                            </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
                <div class="block">
                    <div class="block-heading">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                <h1><?=t('app','Change Email Address');?><span class="info"><?=t('app','Choose a unique email for log in');?>.</span></h1>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <a href="#email-block" data-toggle="collapse" class="btn-trigger-collapse collapsed">
                                    <span class="closed info-title"><?= t('app', 'Open')?></span>
                                    <span class="info-title"><?= t('app', 'Close')?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="block-body collapse" id="email-block">
                        <?php $form = ActiveForm::begin([
                            'id'        => 'form-change-email',
                            'method'    => 'post',
                            'action'    => ['account/update-email'],
                            'enableAjaxValidation' => false,
                        ]); ?>
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                    <?= $form->field($modelEmail, 'passwordCurrent', [
                                            'template'      => '{input} {error}',
                                    ])->passwordInput([ 'placeholder' => t('app','Enter current password'), 'class' => ''])->label(false); ?>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                    <?= $form->field($modelEmail, 'newEmail', [
                                            'template'      => '{input} {error}',
                                    ])->textInput([ 'placeholder' => t('app','Enter New Email'), 'class' => ''])->label(false); ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <button type="submit" class="btn-as" value="Submit"><?=t('app','Submit');?></button>
                                </div>
                            </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
                <div class="block">
                    <div class="block-heading">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                <h1><?=t('app','Terminate Account');?><span class="info"><?=t('app','Close your account');?></span></h1>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <a href="#terminate-block" data-toggle="collapse" class="btn-trigger-collapse collapsed">
                                    <span class="closed info-title"><?= t('app', 'Open')?></span>
                                    <span class="info-title"><?= t('app', 'Close')?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="block-body collapse" id="terminate-block">
                            <?php $form = ActiveForm::begin([
                                'id'        => 'form-terminate-account',
                                'method'    => 'post',
                                'action'    => ['account/terminate-account'],
                                'enableAjaxValidation' => false,
                            ]); ?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <p><?=t('app','Are you sure you want to delete this account?');?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <button type="submit" class="btn-as" value="Sure"><?=t('app','I\'m Sure');?></button>
                                </div>
                            </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
