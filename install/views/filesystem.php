<?php defined('INST_INSTALLER_PATH') || exit('No direct script access allowed');

/**
 *
 * @package    EasyAds
 * @author     CodinBit <contact@codinbit.com>
 * @link       https://www.easyads.io
 * @copyright  2017 EasyAds (https://www.easyads.io)
 * @license    https://www.easyads.io
 * @since      1.0
 */

?>

<?php if(empty($result['summary']['errors']) && empty($result['summary']['warnings'])) { ?>
    <div class="alert alert-success alert-block">
        Congratulations! Your server configuration satisfies all requirements by EasyAds.
    </div>
<?php } elseif(!empty($result['summary']['warnings'])) { ?>
    <div class="alert alert-warning alert-block">
        Your server configuration satisfies the minimum requirements by EasyAds.<br />
        Please pay attention to the warnings listed below if your application will use the corresponding features.
    </div>
<?php } else { ?>
    <div class="alert alert-danger alert-block">
        Unfortunately your server configuration does not satisfy the requirements by EasyAds.
    </div>
<?php } ?>

<form method="post">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">File System Checks</h3>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <tr>
                        <th>Name</th>
                        <th>Result</th>
                        <th>Required by</th>
                        <th>Memo</th>
                    </tr>
                    <?php foreach($result['requirements'] as $requirement): ?>
                        <tr>
                            <td><?php echo $requirement['name']; ?></td>
                            <td class="<?php echo $requirement['condition'] ? 'success' : ($requirement['error'] ? 'danger' : 'warning'); ?>">
                                <?php echo $requirement['condition'] ? 'Passed' : ($requirement['error'] ? 'Failed' : 'Warning'); ?>
                            </td>
                            <td><?php echo $requirement['by']; ?></td>
                            <td><?php echo $requirement['memo']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <div class="clearfix"><!-- --></div>
        </div>
        <div class="box-footer">
            <div class="pull-right">
                <button class="btn btn-primary btn-flat" value="<?php echo empty($result['summary']['errors']) ? 1 : 0; ?>" name="result"><?php if (empty($result['summary']['errors'])) { ?> Next <?php } else { ?> Check again <?php }?></button>
            </div>
            <div class="clearfix"><!-- --></div>
        </div>
    </div>
</form>