<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
?>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-push-2 col-md-12 col-sm-12 col-xs-12">
                <?php $form = ActiveForm::begin([
                    'method'      => 'get',
                    'fieldConfig' => ['options' => ['class' => 'input-group']],
                    'action'      => ['site/search'],
                    'options'     => [
                        'class' => 'search-form'
                    ]
                ]); ?>
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                        <?= $form->field($searchModel, 'searchPhrase', [
                            'template' => "{label}\n<i class='fa fa-search' aria-hidden='true'></i>\n{input}\n{hint}\n{error}"
                        ])->textInput(['placeholder' => t('app', 'ICO name')])->label(false) ?>
                    </div>
                    
                    <div class="col-lg-4 col-md-4 col-sm-2 col-xs-12">
                        <?= Html::submitButton(t('app', 'Search ICO'), ['class' => 'btn-as']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>