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
            <h3 class="box-title">Admin credentials</h3>
        </div>
        <div class="box-body">
            <div class="col-lg-12">
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label class="required">First name <span class="required">*</span></label>
                        <input class="form-control has-help-text<?php echo $context->getError('first_name') ? ' error':'';?>" name="first_name" type="text" value="<?php echo getPost('first_name', '');?>"/>
                        <?php if ($error = $context->getError('first_name')) { ?>
                            <div class="errorMessage" style="display: block;"><?php echo $error;?></div>
                        <?php } ?>
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="required">Last name <span class="required">*</span></label>
                        <input class="form-control has-help-text<?php echo $context->getError('last_name') ? ' error':'';?>" name="last_name" type="text" value="<?php echo getPost('last_name', '');?>"/>
                        <?php if ($error = $context->getError('last_name')) { ?>
                            <div class="errorMessage" style="display: block;"><?php echo $error;?></div>
                        <?php } ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label class="required">Email <span class="required">*</span></label>
                        <input class="form-control has-help-text<?php echo $context->getError('email') ? ' error':'';?>" name="email" type="text" value="<?php echo getPost('email', '');?>"/>
                        <?php if ($error = $context->getError('email')) { ?>
                            <div class="errorMessage" style="display: block;"><?php echo $error;?></div>
                        <?php } ?>
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="required">Password <span class="required">*</span></label>
                        <input class="form-control has-help-text<?php echo $context->getError('password') ? ' error':'';?>" name="password" type="text" value="<?php echo getPost('password', '');?>"/>
                        <?php if ($error = $context->getError('password')) { ?>
                            <div class="errorMessage" style="display: block;"><?php echo $error;?></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <div class="pull-right">
                <button type="submit" name="next" value="1" class="btn btn-primary btn-flat">Create account</button>
            </div>
            <div class="clearfix"><!-- --></div>
        </div>
    </div>
</form>