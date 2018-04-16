<?php

use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;

?>
<div class="content-wrapper">
    <?= notify()->show();?>
    <div class="notify-wrapper">
    </div>
    <section class="content-header">
        <h1>&nbsp;</h1>
        <?=
        Breadcrumbs::widget(
            [
                'homeLink' => ['label' => t('app', 'Home'),
                               'url' => app()->params['adminUrl']],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        ) ?>
    </section>
    <section class="content <?= app()->controller->id . '-' . app()->controller->action->id;?>">
        <?= Alert::widget() ?>
        <?= $content ?>
    </section>
</div>

<footer class="main-footer">
    <div class="hidden-xs">
        <b><?=t('app', 'Version');?></b> <?=app()->options->get("app.data.version", "1.0");?>
    </div>

</footer>