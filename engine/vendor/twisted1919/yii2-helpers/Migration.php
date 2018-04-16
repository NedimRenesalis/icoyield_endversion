<?php

namespace twisted1919\helpers;

use Symfony\Component\Finder\Finder;
use yii\base\Component;
use yii\db\Query;

/**
 * Class Migration
 * @package app\helpers
 */
class Migration extends Component
{
    /**
     * The name of the dummy migration that marks the beginning of the whole migration history.
     */
    const BASE_MIGRATION = 'm000000_000000_base';
    
    /**
     * @var string
     */
    public $db = 'db';

    /**
     * @var string
     */
    public $migrationsPath = '@app/migrations';

    /**
     * @var string
     */
    public $migrationsTable = '{{%migration}}';

    /**
     * @var string
     */
    public $migrationsNamespace = '\\';

    /**
     * @var string
     */
    public $error = '';

    /**
     * @var string
     */
    public $output = '';

    /**
     * @param int $maxUp
     * @param string $method
     * @return $this
     */
    public function up($maxUp = 1, $method = 'up')
    {
        /* make sure this stays valid */
        $maxUp = $maxUp >= 0 ? $maxUp : 0;

        /* set the counter */
        $counter = 0;

        /* in case the table does not exists, create it */
        $this->createMigrationsHistoryTable();
        
        /* search for migration files */
        $finder = new Finder();
        $finder
            ->depth('== 0')
            ->followLinks(false)
            ->ignoreDotFiles(true)
            ->filter(function(\SplFileInfo $file){

                /* get the version by striping the .php extension */
                $version = $file->getBasename('.php');

                /* check if this particular migration has been applied */
                $row = (new Query())
                    ->from($this->migrationsTable)
                    ->where(['version' => $version])
                    ->one(app_get($this->db));

                /* if it has been applied, skip it */
                if (!empty($row['version'])) {
                    return false;
                }

                return true;
            })
            ->sortByName()
            ->files()
            ->in(\Yii::getAlias($this->migrationsPath));

        /* start capturing the output */
        ob_start();
        
        /* run each migration that hasn't been applied */
        foreach ($finder as $file) {

            /* make sure we don't go over the limit, if any */
            if ($maxUp > 0 && $counter > 0 && $counter >= $maxUp) {
                break;
            }

            $version   = $file->getBasename('.php');
            $className = $this->migrationsNamespace . $version;

            if (!class_exists($className, false)) {
                require_once $file->getRealPath();
            }
            $migration = new $className();

            try {
                /* run the migration */
                $migration->$method();
                $counter++;
            } catch (\Exception $e) {
                $this->error = $e->getMessage();
                break;
            }

            /* update the migrations table */
            app_get($this->db)->createCommand()->insert($this->migrationsTable, [
                'version'    => $version,
                'apply_time' => time(),
            ])->execute();
        }

        /* store the output */
        $this->output = ob_get_contents();

        /* clean the buffer */
        ob_end_clean();

        return $this;
    }

    /**
     * @param int $maxDown
     * @param string $method
     * @return $this
     */
    public function down($maxDown = 1, $method = 'down')
    {
        /* make sure this stays valid */
        $maxDown = $maxDown >= 0 ? $maxDown : 0;

        /* set the counter */
        $counter = 0;

        /* in case the table does not exists, create it */
        $this->createMigrationsHistoryTable();

        /* search for migration files */
        $finder = new Finder();
        $finder
            ->depth('== 0')
            ->followLinks(false)
            ->ignoreDotFiles(true)
            ->filter(function(\SplFileInfo $file){

                /* get the version by striping the .php extension */
                $version = $file->getBasename('.php');

                /* check if this particular migration has been applied */
                $row = (new Query())
                    ->from($this->migrationsTable)
                    ->where(['version' => $version])
                    ->one(app_get($this->db));

                /* if it has not been applied, skip it */
                if (empty($row['version'])) {
                    return false;
                }

                return true;
            })
            ->sort(function ($a, $b) {
                return strcmp($b->getRealpath(), $a->getRealpath());
            })
            ->files()
            ->name('*.php')
            ->in(\Yii::getAlias($this->migrationsPath));

        /* start capturing the output */
        ob_start();
        
        /* run each migration that hasn't been applied */
        foreach ($finder as $file) {

            /* make sure we don't go over the limit, if any */
            if ($maxDown > 0 && $counter > 0 && $counter >= $maxDown) {
                break;
            }

            $version   = $file->getBasename('.php');
            $className = $this->migrationsNamespace . $version;

            if (!class_exists($className, false)) {
                require_once $file->getRealPath();
            }
            $migration = new $className();

            try {
                /* run the migration */
                $migration->$method();
                $counter++;
            } catch (\Exception $e) {
                $this->error = $e->getMessage();
                break;
            }

            /* delete from the migrations table */
            app_get($this->db)->createCommand()->delete($this->migrationsTable, [
                'version' => $version,
            ])->execute();
        }

        /* store the output */
        $this->output = ob_get_contents();

        /* clean the buffer */
        ob_end_clean();

        return $this;
    }

    /**
     * @param int $maxUp
     * @return $this
     */
    public function safeUp($maxUp = 1)
    {
        return $this->up($maxUp, 'safeUp');
    }

    /**
     * @param $maxDown
     * @return $this
     */
    public function safeDown($maxDown = 1)
    {
        return $this->down($maxDown, 'safeDown');
    }

    /**
     * Creates the migrations history table.
     */
    protected function createMigrationsHistoryTable()
    {
        $db = app_get($this->db);
        if ($db->schema->getTableSchema($this->migrationsTable, true) !== null) {
            return;
        }

        $db->createCommand()->createTable($this->migrationsTable, [
            'version'    => 'varchar(180) NOT NULL PRIMARY KEY',
            'apply_time' => 'integer',
        ])->execute();
        
        $db->createCommand()->insert($this->migrationsTable, [
            'version'    => self::BASE_MIGRATION,
            'apply_time' => time(),
        ])->execute();
    }
}