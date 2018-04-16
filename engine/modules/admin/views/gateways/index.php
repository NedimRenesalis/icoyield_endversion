<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = t('app', 'Gateways');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <?php app()->trigger('admin.gateways.tab'); ?>
    </ul>
    <?php $form = ActiveForm::begin(); ?>
    <div class="tab-content">
        <?php app()->trigger('admin.gateways.form'); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
