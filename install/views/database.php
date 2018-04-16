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
<form action="" method="post">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Database credentials and import</h3>
        </div>
        <div class="box-body">
            <div class="col-lg-12">
                <div class="row">
                    <div class="form-group col-lg-10">
                        <label class="required">Hostname <span class="required">*</span></label>
                        <input class="form-control has-help-text<?php echo $context->getError('hostname') ? ' error':'';?>" name="hostname" type="text" value="<?php echo getPost('hostname', 'localhost');?>"/>
                        <?php if ($error = $context->getError('hostname')) { ?>
                            <div class="errorMessage" style="display: block;"><?php echo $error;?></div>
                        <?php } ?>
                    </div>
                    <div class="form-group col-lg-2 pull-right">
                        <label class="required">Port</label>
                        <input class="form-control has-help-text<?php echo $context->getError('port') ? ' error':'';?>" name="port" type="text" value="<?php echo getPost('port', '');?>"/>
                        <?php if ($error = $context->getError('port')) { ?>
                            <div class="errorMessage" style="display: block;"><?php echo $error;?></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label class="required">Username</label>
                        <input class="form-control has-help-text<?php echo $context->getError('username') ? ' error':'';?>" name="username" type="text" value="<?php echo getPost('username');?>"/>
                        <?php if ($error = $context->getError('username')) { ?>
                            <div class="errorMessage" style="display: block;"><?php echo $error;?></div>
                        <?php } ?>
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Password</label>
                        <input class="form-control has-help-text" name="password" type="text" value="<?php echo getPost('password');?>"/>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="row">
                    <div class="form-group col-lg-10">
                        <label class="required">Database name <span class="required">*</span></label>
                        <input class="form-control has-help-text<?php echo $context->getError('dbname') ? ' error':'';?>" name="dbname" type="text" value="<?php echo getPost('dbname');?>"/>
                        <?php if ($error = $context->getError('dbname')) { ?>
                            <div class="errorMessage" style="display: block;"><?php echo $error;?></div>
                        <?php } ?>
                    </div>
                    <div class="form-group col-lg-2 pull-right">
                        <label>Tables prefix</label>
                        <input class="form-control has-help-text<?php echo $context->getError('prefix') ? ' error':'';?>" name="prefix" type="text" value="<?php echo getPost('prefix', 'ea_');?>"/>
                        <?php if ($error = $context->getError('prefix')) { ?>
                            <div class="errorMessage" style="display: block;"><?php echo $error;?></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="clearfix"><!-- --></div>
        </div>
        <div class="box-footer">
            <div class="pull-right">
                <button type="submit" name="next" value="1" class="btn btn-primary btn-flat">Start importing</button>
            </div>
            <div class="clearfix"><!-- --></div>
        </div>
    </div>
</form>
