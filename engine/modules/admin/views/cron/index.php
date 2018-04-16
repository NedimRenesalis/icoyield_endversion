<?php
    $this->title = t('app', 'Cron jobs');
    $this->params['breadcrumbs'][] = ['label' => 'Cron jobs', 'url' => ['/admin/cron']];
?>

<div class="callout callout-warning">
    <h4><i class="icon fa fa-warning"></i> Warning!</h4>
    <p>It's Critical to add this cronjobs to your server in order for the application to work properly!</p>
    <p>It's Critical to have the same version of PHP and PHP-CLI on the server for the application to work properly!</p>
</div>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Cron Jobs - You need to add the following cron jobs to your server</h3>
    </div>
    <div class="box-body">
<pre>

<?php foreach (app\helpers\CommonHelper::getCronJobsList() as $cronJobData) { ?>
# <?php echo $cronJobData['description'];?>

<?php echo $cronJobData['cronjob'];?>


<?php } ?>
</pre>
            If you have a control box like CPanel, Plesk, Webmin etc, you can easily add the cron jobs to the server cron.<br />
            In case you have shell access to your server, following commands should help you add the crons easily:
            <br /><br />

            <pre>
# copy the current cron into a new file
crontab -l > eacron

# add the new entries into the file
<?php foreach (app\helpers\CommonHelper::getCronJobsList() as $cronJobData) { ?>
    echo "<?php echo $cronJobData['cronjob'];?>" >> eacron
<?php } ?>

# install the new cron
crontab eacron

# remove the crontab file since it has been installed and we don't use it anymore.
rm eacron
</pre>

            Or, if you like working with VIM, then you know you can manually add them.<br />
            Open the crontab in edit mode (<code>crontab -e</code>) add the cron jobs and save, that's all.
        <div class="clearfix"><!-- --></div>
    </div>
</div>