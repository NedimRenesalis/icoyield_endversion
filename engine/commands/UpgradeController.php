<?php

namespace app\commands;

use yii\console\Controller;
use yii\helpers\Console;
use twisted1919\helpers\FileHelper;

/**
 * Class UpgradeController
 * @package app\commands
 */
class UpgradeController extends Controller
{
    /**
     * The command entry point
     */
    public function actionIndex()
    {
        $version = options()->get('app.data.version', '1.0');
        if (version_compare($version, APP_VERSION, '>=')) {
            /* inform about the action */
            $this->stdout(t('app', 'You are already at the latest version!') . "\n");
            return self::UNSPECIFIED_ERROR;
        }

        /* get the migration object */
        $migration = app()->migration;

        /* if the update process is initialised */
        $confirm = $this->confirm(t('app', 'Are you sure you want to upgrade the application from version {v1} to {v2}?', [
                'v1' => $version,
                'v2' => APP_VERSION,
            ]) . "\n");

        if (!$confirm) {
            return self::UNSPECIFIED_ERROR;
        }

        /* apply all the migrations */
        $migration->up(0);

        /* stop on error and print why */
        if ($migration->error) {

            $this->stderr(t('app', 'The upgrade failed with following message:') . "\n", Console::FG_RED, Console::UNDERLINE);
            $this->stdout($migration->error . "\n");

            $this->stderr(t('app', 'Here is a transcript of the process:') . "\n", Console::FG_RED, Console::UNDERLINE);
            $this->stdout($migration->output . "\n");

            return self::UNSPECIFIED_ERROR;

        }

        /* set the new application version */
        options()->set('app.data.version', APP_VERSION);

        /* flush the caches */
        cache()->flush();

        /* empty the runtime folder */
        FileHelper::deleteDirectoryContents(\Yii::getAlias('@app/runtime'));

        /* empty the assets cache */
        FileHelper::deleteDirectoryContents(realpath(\Yii::getAlias('@app/../assets/cache')));

        /* put back the .gitignore file */
        $gitignore = file_get_contents(\Yii::getAlias('@app/data/gitignore-all-but-it.txt'));
        file_put_contents(\Yii::getAlias('@app/runtime/.gitignore'), $gitignore);
        file_put_contents(realpath(\Yii::getAlias('@app/../assets/cache/.gitignore')), $gitignore);

        /* inform about the action */
        $this->stdout(t('app', 'Congratulations, your application has been successfully upgraded to version {version}', [
                'version' => '<span class="badge">' . APP_VERSION . '</span>',
            ]) . "\n");

        return self::EXIT_CODE_SUCCESS;
    }
}
