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
 * Class DatabaseController
 */
class DatabaseController extends Controller
{
    /**
     * Main action
     */
    public function actionIndex()
    {

        if (!getSession('filesystem') || !getSession('license_data')) {
            redirect('index.php?route=filesystem');
        }

        $this->validateRequest();

        if (getSession('database')) {
            redirect('index.php?route=admin');
        }

        $this->data['pageHeading'] = 'Database import';
        $this->data['breadcrumbs'] = array(
            'Database import' => 'index.php?route=database',
        );

        $this->render('database');
    }

    /**
     * @return $this
     */
    protected function validateRequest()
    {
        if (!getPost('next')) {
            return $this;
        }

        $dbHost = trim(getPost('hostname'));
        $dbPort = trim(getPost('port'));
        $dbName = trim(getPost('dbname'));
        $dbUser = trim(getPost('username'));
        $dbPass = isset($_POST['password']) ? $_POST['password'] : null; // keep original
        $dbPrefix = trim(getPost('prefix'));

        if (empty($dbHost)) {
            $this->addError('hostname', 'Please provide your database hostname!');
        } elseif (!preg_match('/^([a-z0-9\_\-\.=\/\\\]+)$/i', $dbHost)) {
            $this->addError('hostname', 'The hostname contains invalid characters!');
        }

        if (!empty($dbPort) && !is_numeric($dbPort)) {
            $this->addError('port', 'The port value must be a number, usualy 3306!');
        }

        if (empty($dbName)) {
            $this->addError('dbname', 'Please provide your database name!');
        } elseif (!preg_match('/^([a-z0-9\_\-]+)$/i', $dbName)) {
            $this->addError('dbname', 'Database name must contain only letters, numbers and underscores!');
        }

        if (!empty($dbPrefix) && !preg_match('/^([a-z0-9\_]+)$/', $dbPrefix)) {
            $this->addError('prefix', 'Tables prefix must contain only lowercase letters, numbers and underscores!');
        }

        if ($this->hasErrors()) {
            return $this->addError('general', 'Your form has a few errors, please fix them and try again!');
        }

        try {
            if (strpos($dbHost, 'unix_socket=') === 0) {
                $dbConnectionString = sprintf('mysql:%s;dbname=%s;', $dbHost, $dbName);
            } else {
                $dbConnectionString = sprintf('mysql:host=%s;dbname=%s', $dbHost, $dbName);
                if (!empty($dbPort)) {
                    $dbConnectionString = sprintf('mysql:host=%s;port=%d;dbname=%s', $dbHost, (int)$dbPort, $dbName);
                }
            }
            $dbh = new PDO($dbConnectionString, $dbUser, $dbPass);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            return $this->addError('general', $e->getMessage());
        }

        $contents = @file_get_contents(INST_DB_FILE_DEFINITION);
        if (empty($contents)) {
            return $this->addError('general', 'Unable to open the definition configuration file!');
        }

        $searchReplace = array(
            '{DB_CONNECTION_STRING}'    => $dbConnectionString,
            '{DB_USER}'                 => $dbUser,
            '{DB_PASS}'                 => $dbPass,
            '{DB_PREFIX}'               => $dbPrefix,
        );

        $contents = str_replace(array_keys($searchReplace), array_values($searchReplace), $contents);
        if (!@file_put_contents(INST_DB_FILE, $contents)) {
            return $this->addError('general', 'Unable to write the configuration file!');
        }

        $contents = @file_get_contents(INST_REQUEST_FILE_DEFINITION);
        if (empty($contents)) {
            return $this->addError('general', 'Unable to open the definition configuration file!');
        }

        $searchReplace = [
            '{COOKIE_VALIDATION_KEY}' => md5(uniqid(rand(0, time()), true))
            ];

        $contents = str_replace(array_keys($searchReplace), array_values($searchReplace), $contents);
        if (!@file_put_contents(INST_REQUEST_FILE, $contents)) {
            return $this->addError('general', 'Unable to write the configuration file!');
        }

        /* mark config file creation */
        setSession('config_file_created', 1);

        /* try to force the mode of the file/dir */
        @chmod(dirname(INST_MAIN_CONFIG_FILE), 0755);
        @chmod(INST_MAIN_CONFIG_FILE, 0555);

        /* get the app instance */
        $app = appInstance();

        /* capture the output */
        ob_start();

        /* start migration of tables */
        $app->migration->up(0);

        if ($app->migration->error) {
            return $this->addError('general', $app->migration->error);
        }

        /* create the options table. */
        require INST_VENDOR_PATH . '/twisted1919/yii2-options/migrations/m160808_182000_create_option_table.php';
        $migration = new m160808_182000_create_option_table();
        $migration->removeMigrationHistory = false;

        $migration->up();

        /* discard the output */
        ob_end_clean();

        /* set the app version */
        $app->options->set("app.data.version", APP_VERSION);

        /* set the app license */
        $licenseData = (array)getSession('license_data', array());
        $app->options->set("app.settings.license.firstName", $licenseData['first_name']);
        $app->options->set("app.settings.license.lastName", $licenseData['last_name']);
        $app->options->set("app.settings.license.email", $licenseData['email']);
        $app->options->set("app.settings.license.purchaseCode", $licenseData['purchase_code']);
        $app->options->set("app.settings.license.marketplace", $licenseData['marketplace']);

        setSession('database', 1);

        return $this;
    }
}
