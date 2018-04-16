<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

$this->title = t('app', 'Extension Manager');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary customers-index">

    <div class="box-header">
        <div class="pull-left">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="pull-right">
            <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-cloud-upload fa-fw']) . t('app', 'Upload extension'), ['#uploadExtensionModal'], ['class' => 'btn btn-xs btn-success', 'data-toggle' => 'modal']) ?>
            <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-refresh fa-fw']) . t('app', 'Refresh'), ['index'], ['class' => 'btn btn-xs btn-success']) ?>
        </div>
    </div>

    <div class="box-body">
        <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'id'           => 'extensions',
            'tableOptions' => [
                'class' => 'table table-bordered table-hover table-striped',
            ],
            'options'      => ['class' => 'table-responsive grid-view'],
            'dataProvider' => $model,
            'columns'      => [
                'name',
                'description',
                'version',
                'type',
                'author',
                [
                    'header'         => t('app', 'Options'),
                    'class'          => 'yii\grid\ActionColumn',
                    'contentOptions' => [
                        'style' => 'width:60px',
                        'class' => 'table-actions'
                    ],
                    'template'       => '{toggle} {delete}',
                    'buttons'        => [
                        'toggle' => function ($url, $model) {
                            $icon = ($model['status'] == 'enabled') ? '<i class="fa fa-times"></i>' : '<i class="fa fa-check"></i>';
                            $uri = ($model['status'] == 'enabled') ? '/admin/extensions/disable' : '/admin/extensions/enable';
                            $label = ($model['status'] == 'enabled') ? t('app', 'Disable') : t('app', 'Enable');

                            return Html::a(
                                $icon,
                                [$uri, 'id' => $model['id']],
                                [
                                    'style' => 'margin-right: 5px',
                                    'data'  => [
                                        'pjax'      => '0',
                                        'content'   => $label,
                                        'container' => 'body',
                                        'toggle'    => 'popover',
                                        'trigger'   => 'hover',
                                        'placement' => 'top',
                                    ],
                                ]
                            );
                        },

                        'delete' => function ($url, $model) {
                            return Html::a(
                                '<i class="fa fa-trash"></i>',
                                ['/admin/extensions/delete'],
                                [
                                    'style' => 'margin-right: 5px',
                                    'data'  => [
                                        'pjax'      => '0',
                                        'content'   => t('app', 'Delete'),
                                        'container' => 'body',
                                        'toggle'    => 'popover',
                                        'trigger'   => 'hover',
                                        'placement' => 'top',
                                        'method'    => 'post',
                                        'params'    => ['id' => $model['id']],
                                        'confirm'   => t('app', 'Are you sure you want to delete this extension?'),
                                    ],
                                ]
                            );
                        },
                    ],
                ],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>

</div>

<!-- MODAL UPLOAD EXTENSION -->
<div class="modal fade" id="uploadExtensionModal" tabindex="-1" role="dialog" aria-labelledby="uploadExtensionArchive"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div>
                <div class="modal-header" id="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php echo t('app', 'Upload extension archive.') ?></h4>
                    <h4 class="modal-error"</div>
            </div>
            <div class="modal-body" id="modal-body">
                <div class="modal-message">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="callout callout-info"><?= t('app', 'Please note that only zip files are allowed for upload.') ?></div>
                            <div class="file-upload-error" style="display: none"></div>
                            <label class="control-label"><?= t('app', 'Archive') ?></label>
                            <?= \kartik\file\FileInput::widget([
                                'name'          => 'extensionFile',
                                'options'       => [
                                    'id'     => 'extension-input',
                                    'accept' => '.zip'
                                ],
                                'pluginOptions' => [
                                    'language'              => options()->get('app.settings.common.siteLanguage', 'en'),
                                    'uploadUrl'             => Url::to(['extensions/upload']),
                                    'showPreview'           => false,
                                    'uploadLabel'           => t('app', 'Upload extension'),
                                    'uploadIcon'            => '<i class="fa fa-cloud-upload fa-fw"></i>',
                                    'elErrorContainer'      => '.file-upload-error',
                                    'msgErrorClass'         => 'alert alert-block alert-danger',
                                    'layoutTemplates'       => ['main2' => '{preview} {remove} {browse}'],
                                    'allowedFileExtensions' => ["zip"],
                                ]
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    <?php $this->registerJs(
        "$('#extension-input').on('fileuploaded', function (event, data, previewId, index) {
            if (!data.response.isSuccess) {
                var errorContainer = $('.file-upload-error');
                errorContainer.text(data.response.message);
                errorContainer.addClass('alert alert-block alert-danger');
                errorContainer.show();
            } else {
                $('#uploadExtensionModal').modal('hide');
                location.reload(true);
            }
        });"
    ); ?>
</script>