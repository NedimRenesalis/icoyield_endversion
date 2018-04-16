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
 * Class FilesystemController
 */
class FilesystemController extends Controller
{
    /**
     * Main action
     */
    public function actionIndex()
    {
        if (!getSession('requirements') || !getSession('license_data')) {
            redirect('index.php?route=requirements');
        }

        require_once INST_VENDOR_PATH . '/yiisoft/yii2/requirements/YiiRequirementChecker.php';
        $checker = new YiiRequirementChecker();
        $result  = $checker->check($this->getRequirements())->getResult();

        if (getPost('result', 0) && empty($result['summary']['errors'])) {
            setSession('filesystem', 1);
            redirect('index.php?route=database');
        }

        $this->data['result'] = $result;

        $this->data['pageHeading'] = 'File System';
        $this->data['breadcrumbs'] = array(
            'File system checks' => 'index.php?route=filesystem',
        );

        $this->render('filesystem');
    }

    /**
     * @return array
     */
    protected function getRequirements()
    {
        $configDir      = INST_APP_PATH  . '/config';
        $runtimeDir     = INST_APP_PATH  . '/runtime';
        $assetsCacheDir = INST_ROOT_PATH . '/assets/cache';
        $uploadsDir     = INST_ROOT_PATH . '/uploads';
        $imagesDir      = INST_ROOT_PATH . '/uploads/images';
        $avatarDir      = INST_ROOT_PATH . '/uploads/images/avatar';
        $siteDir        = INST_ROOT_PATH . '/uploads/images/site';
        $listingDir     = INST_ROOT_PATH . '/uploads/images/listings';

        return array(
            array(
                'name'      => 'Main configuration directory <br /> ' . $configDir,
                'mandatory' => true,
                'condition' => file_exists($configDir) && is_dir($configDir) && (@chmod($configDir, 0777) || is_writable($configDir)),
                'by'        => 'EasyAds',
                'memo'      => 'The directory must be writable by the web server (chmod 0777).',
            ),
            array(
                'name'      => 'Runtime directory <br />' . $runtimeDir,
                'mandatory' => true,
                'condition' => file_exists($runtimeDir) && is_dir($runtimeDir) && (@chmod($runtimeDir, 0777) || is_writable($runtimeDir)),
                'by'        => 'EasyAds',
                'memo'      => 'The directory must be writable by the web server (chmod 0777).',
            ),
            array(
                'name'      => 'Assets cache directory <br /> ' . $assetsCacheDir,
                'mandatory' => true,
                'condition' => file_exists($assetsCacheDir) && is_dir($assetsCacheDir) && (@chmod($assetsCacheDir, 0777) || is_writable($assetsCacheDir)),
                'by'        => 'EasyAds',
                'memo'      => 'The directory must be writable by the web server (chmod 0777).',
            ),
            array(
                'name'      => 'Uploads directory <br /> ' . $uploadsDir,
                'mandatory' => true,
                'condition' => file_exists($assetsCacheDir) && is_dir($assetsCacheDir) && (@chmod($assetsCacheDir, 0777) || is_writable($assetsCacheDir)),
                'by'        => 'EasyAds',
                'memo'      => 'The directory must be writable by the web server (chmod 0777).',
            ),
            array(
                'name'      => 'Uploads images directory <br /> ' . $imagesDir,
                'mandatory' => true,
                'condition' => file_exists($imagesDir) && is_dir($imagesDir) && (@chmod($imagesDir, 0777) || is_writable($imagesDir)),
                'by'        => 'EasyAds',
                'memo'      => 'The directory must be writable by the web server (chmod 0777).',
            ),
            array(
                'name'      => 'Uploads avatar directory <br /> ' . $avatarDir,
                'mandatory' => true,
                'condition' => file_exists($avatarDir) && is_dir($avatarDir) && (@chmod($avatarDir, 0777) || is_writable($avatarDir)),
                'by'        => 'EasyAds',
                'memo'      => 'The directory must be writable by the web server (chmod 0777).',
            ),
            array(
                'name'      => 'Uploads site directory <br /> ' . $siteDir,
                'mandatory' => true,
                'condition' => file_exists($siteDir) && is_dir($siteDir) && (@chmod($siteDir, 0777) || is_writable($siteDir)),
                'by'        => 'EasyAds',
                'memo'      => 'The directory must be writable by the web server (chmod 0777).',
            ),
            array(
                'name'      => 'Uploads listings directory <br /> ' . $listingDir,
                'mandatory' => true,
                'condition' => file_exists($listingDir) && is_dir($listingDir) && (@chmod($listingDir, 0777) || is_writable($listingDir)),
                'by'        => 'EasyAds',
                'memo'      => 'The directory must be writable by the web server (chmod 0777).',
            ),
        );
    }

}
