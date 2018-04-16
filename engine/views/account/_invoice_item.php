<?php
use \yii\helpers\Html;
?>
<li class="number"><?= html_encode($model->getReference()); ?></li>
<li class="date"><?= html_encode(app()->formatter->asDate($model->created_at));?></li>
<li class="total"><?= html_encode(app()->formatter->asCurrency($model->order->total)); ?></li>
<li class="actions icons">
    <?= Html::a('<i class="fa fa-download" aria-hidden="true"></i><span>' . t('app','Download') . '</span>', ['account/generate-invoice-pdf', 'id' => html_encode($model->invoice_id)], ['class'=>'btn-as', 'target' => '_blank', 'data-pjax' => 0]); ?>
    <?= Html::a('<i class="fa fa-envelope-o" aria-hidden="true"></i><span>' . t('app','Email') . '</span>', ['account/send-invoice', 'id' => html_encode($model->invoice_id)], ['class'=>'btn-as reverse', 'data-pjax' => 0]); ?>
</li>