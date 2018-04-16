<div class="box box-primary widget-table-box">
    <div class="box-header with-border">
        <h3 class="box-title"><?=html_encode($title);?></h3>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table no-margin">
                <thead>
                <tr>
                    <?php foreach ($columns as $key => $column) { ?>
                    <th><?=html_encode($column);?></th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($data as $item) {
                ?>
                    <tr>
                    <?php
                    foreach ($columns as $key=>$column) {
                    ?>
                        <td>
                            <?= html_encode($item->$key); ?>
                        </td>
                    <?php
                    }
                    ?>
                    </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="box-footer clearfix">
        <a href="<?=$link;?>" class="btn btn-sm btn-default btn-flat pull-right"><?=t('app', 'View all items');?></a>
    </div>
</div>