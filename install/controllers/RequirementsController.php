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

/**
 * Class RequirementsController
 */
class RequirementsController extends Controller
{
    /**
     * Main action
     */
    public function actionIndex()
    {
        if (!getSession('welcome') || !getSession('license_data')) {
            redirect('index.php?route=welcome');
        }

        require_once INST_VENDOR_PATH . '/yiisoft/yii2/requirements/YiiRequirementChecker.php';
        $checker = new YiiRequirementChecker();
        $result  = $checker->check(INST_APP_PATH . '/data/requirements.php')->getResult();

        if (!empty($_POST['result']) && empty($result['summary']['errors'])) {
            setSession('requirements', 1);
            redirect('index.php?route=filesystem');
        }

        $this->data['result'] = $result;

        $this->data['pageHeading'] = 'Requirements';
        $this->data['breadcrumbs'] = array(
            'Requirements' => 'index.php?route=requirements',
        );

        $this->render('requirements');
    }

}
