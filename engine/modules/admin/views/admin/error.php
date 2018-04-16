<?php
$this->title = $name;
?>
<div class="lockscreen-wrapper">
    <div class="site-error">
        <h1><?= html_encode($this->title) ?></h1>
        <div class="alert alert-danger">
            <?= nl2br(html_encode($message)) ?>
        </div>
    </div>
</div>