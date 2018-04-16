<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\BaseHtml;


$this->title = t('app', 'Theme Settings');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#admin"><?=t('app','Admin theme');?></a></li>
        <li><a data-toggle="tab" href="#site"><?=t('app','Site theme');?></a></li>
        <li class="pull-right">
            <?= Html::a('<i class="fa fa-ban" aria-hidden="true"></i>&nbsp&nbsp'.t('app', 'Cancel'), ['index'], ['class' => 'btn btn-xs btn-success']) ?>
        </li>
    </ul>
    <?php $form = ActiveForm::begin(); ?>
    <div class="tab-content">
        <div id="admin" class="tab-pane fade in active">
            <div class="box-body">
                <div class="admintheme-settings-form">
                    <div class="clearfix"><!-- --></div>
                        <?= $form->field($model, 'adminSkin')->dropDownList($model->getSkinsList());?>
                        <?= $form->field($model, 'adminLayout')->dropDownList($model->getLayoutsList());?>
                        <?= $form->field($model, 'adminSidebar')->dropDownList($model->getSidebarsList());?>
                    <div class="clearfix"><!-- --></div>
                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        <?= Html::submitButton(t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
                    </div>
                    <div class="clearfix"><!-- --></div>
                </div>
            </div>
        </div>
        <div id="site" class="tab-pane fade in">
            <div class="box-body">
                <div class="sitetheme-settings-form">
                    <div class="row">
                        <div class="col-lg-6">
                                <?php $logo = options()->get('app.settings.theme.siteLogo', \Yii::getAlias('@web/assets/site/img/logo.png'));?>
                                <?= $form->field($model, 'siteLogoUpload')->widget(\kartik\file\FileInput::classname(), [
                                    'options' => [
                                        'class'=>'image-upload',
                                        'data-image'=>$logo,
                                    ],
                                    'pluginOptions' => [
                                        'language' => options()->get('app.settings.common.siteLanguage', 'en'),
                                        'overwriteInitial'=> true,
                                        'showClose'=> false,
                                        'showRemove' => false,
                                        'showCaption'=> false,
                                        'showBrowse'=> true,
                                        'browseOnZoneClick'=> true,
                                        'removeLabel'=> '',
                                        'removeIcon'=> '<i class="glyphicon glyphicon-remove"></i>',
                                        'removeTitle'=> 'Cancel or reset changes',
                                        'elErrorContainer'=> '.image-upload-error',
                                        'msgErrorClass'=> 'alert alert-block alert-danger',
                                        'defaultPreviewContent'=> '<img src="'.$logo.'" alt="Your Avatar" style="width:400px">',
                                        'layoutTemplates'=> ['main2'=> '{preview} {remove} {browse}'],
                                        'allowedFileTypes' => ["image"],
                                    ]
                                ])->label();
                                ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                           <div class="alert alert-block alert-warning">
                               <ul>
                                   <li><?=t('app','Important: Please be careful what code you include below, this could affect application\'s security!');?></li>
                               </ul>
                           </div>
                            <br />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $form->field($model, 'customCss')->textarea([
                                'class'             => 'form-control',
                                'rows'              => 10,
                                'cols'              => 50,
                                'data-content'      => t('app', 'Custom CSS for your website'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top'
                            ])->label() ?>
                            <p>
                                <?= t('app', 'Note: The above code should be wrapped within &ltstyle&gt tag!'); ?>
                            </p>
                            <hr>
                        </div>
                    </div>
                   <div class="row">
                       <div class="col-lg-12">
                           <?= $form->field($model, 'customJs')->textarea([
                               'class'             => 'form-control',
                               'rows'              => 10,
                               'cols'              => 50,
                               'data-content'      => t('app', 'Custom JS for your site'),
                               'data-container'    => 'body',
                               'data-toggle'       => 'popover',
                               'data-trigger'      => 'hover',
                               'data-placement'    => 'top'
                           ])->label() ?>
                           <p>
                               <?= t('app', 'Note: The above code should be wrapped within &ltscript&gt tag!'); ?>
                           </p>
                       </div>
                   </div>
                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        <?= Html::submitButton(t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
                    </div>
                    <div class="clearfix"><!-- --></div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
