<?php

namespace App\Console\Commands;
use App\Manager\Basic\Ftp;
use App\Manager\Basic\Logger;
use App\Manager\Basic\Reader;
use App\Manager\Basic\Status;
use App\Manager\Basic\Writer;
use App\Model\Cron;
use App\Model\ShopOwner;
use Illuminate\Console\Command;

abstract class AbstractCommand extends Command
{
    const FTP_AUTH_FAILED = 'FTP Credentials Failed.';
    const IN_TEST_MODE = 'Test mode is turned on.';
    const PROCESSING_OFF = 'Processing is turned off.';

    /**
     * @var string $logFileName Name of log file
     */
    public $logFileName = 'log';

    /**
     * The shopify API limit
     *
     * @var int
     */
    public $limit = 250 ;

    /**
     * The name of the Shop Owner's shop
     *
     * @var string
     */
    public $shop ;

    /**
     * Any errors during processing
     *
     * @var array
     */
    public $errors = [];

    /**
     * Ftp or Third Party Url Path
     *
     * @var string
     */
    protected $path;

    /**
     * Basic Writer Class
     *
     * @var Writer $writer
     */
    protected $writer;

    /**
     * ShopOwner DB Model
     *
     * @var ShopOwner $shopOwner
     */
    protected $shopOwner;

    /**
     * Basic Ftp Class
     *
     * @var Ftp $ftp
     */
    protected $ftp;

    /**
     * Ftp File To Be Processed
     *
     * @var string
     */
    protected $fileToProcess;

    /**
     * Delimiter to be used for Reader or Writer Class
     *
     * @var string
     */
    protected $delimiter = "\t" ;

    /**
     * System process on/off
     *
     * @var bool
     */
    protected $isProcessing ;

    /**
     * Date of entity
     *
     * @var \DateTime
     */
    protected $date;

    protected $timezone = 'America/Los_Angeles';

    /**
     * System test mode status
     *
     * @var bool
     */
    protected $testMode;

    /**
     * Basic Reader Class
     *
     * @var Reader $reader
     */
    protected $reader;

    /**
     * Cron Process Currently Running
     *
     * @var Cron $cron
     */
    protected $cron;

    /**
     * AbstractCommand constructor.
     */
    public function __construct()
    {
        $this->writer = new Writer();
        $this->reader = new Reader();

        $this->writer->setDelimiter($this->delimiter)->setIncludeHeader(true);

        $pathToRoot = dirname(dirname(dirname(dirname(__DIR__))));
        $this->fileToProcess =  "$pathToRoot/files/";

        parent::__construct();
    }

    /**
     * Foo function.
     *
     * @param string $pid Process ID
     *
     * @return string
     */
    public function kill($pid)
    {

        $isWindows = stripos(php_uname('s'), 'win') > -1;
        return $isWindows ? exec("taskkill /F /PID $pid") : exec("kill -9 $pid");
    }

    /**
     * Sets Classes with ShopOwner Information
     *
     * @param int $id ShopOwner Id
     *
     * @return $this|bool
     */
    protected function setup( $id = null )
    {

        $id = is_null($id) ? $this->argument('shopOwnerId') : $id;
        $this->shopOwner = $this->shopOwner($id);

        if (!isset($this->shopOwner) || $this->shopOwner == null) {
            return false;
        }

        // ShopOwner Settings
        $this->shop = $this->shopOwner->name;
        $timezone   = $this->shopOwner->timezone();
        $timezone   = is_null($timezone) ? 'America/Los_Angeles' : $timezone;
        $this->timezone = $timezone;
        $this->date = new \DateTime('', new \DateTimeZone($timezone));

        $port       = null ;
        $password   = null;
        $username   = null;
        $host       = null;
        $processing = null;
        $test_mode  = null;

        // Configuration Settings
        if (isset($this->shopOwner->configuration)) {
            foreach ($this->shopOwner->configuration as $key => $value) {
                switch ($value->entity){
                    case 'processing' :
                        $processing = $value->value == 1 ? true : false ;
                        break;
                    case 'test_mode' :
                        $test_mode = $value->value == 1 ? true : false ;
                        break;
                }
            }
        }

        $this->processing($processing);
        $this->testMode($test_mode);

        // Reader Settings
        $this->reader->setDelimiter($this->delimiter);

        return $this;
    }

    /**
     * Create Cron
     *
     * @return null
     */
    protected function createCron()
    {

        $this->cron = new Cron();
        $this->cron->shop_owner_id = $this->shopOwner->id;
        $this->cron->type = Cron::TYPE_DEFAULT;
        $this->cron->status = Status::PENDING;
        $this->cron->name = get_class($this);
        $this->cron->shop = $this->shop;
        $this->cron->pid = getmypid();
        $this->cron->save();
        return null;
    }

    /**
     * Get Shop Owner
     *
     * @param integer $id ID
     *
     * @return mixed|ShopOwner
     */
    public function shopOwner($id)
    {
        return ShopOwner::find($id);
    }

    /**
     * Reset
     *
     * @return null
     */
    public function reset()
    {
        $class = get_class($this);
        Logger::writeToLogFile(
            "Class $class was attempted to be reset.",
            "cron_stop"
        );
        return null;
    }

    /**
     * Path to top level directory
     *
     * @return string
     */
    public function basePath()
    {
        return dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR ;
    }

    /**
     * Get File Folder
     *
     * @param string $folderName Folder Name
     *
     * @return string
     */
    public function fileFolder( $folderName = 'default' )
    {

        $dir = $this->basePath() . 'files' . DIRECTORY_SEPARATOR
            .  $folderName . DIRECTORY_SEPARATOR . $this->shop() .
            DIRECTORY_SEPARATOR ;

        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
            $htaccess = $dir . DIRECTORY_SEPARATOR . '.htaccess' ;
            $file     = fopen($htaccess, 'w');
            fwrite($file, "deny from all");
            fclose($file);
        }
        return $dir;
    }

    /**
     * Write to PHP Console
     *
     * @param array|string $data
     * @param string       $color
     *
     * @return bool
     */
    public function consoleLog( $data , $color = '1;32m')
    {
        if (is_string($data)) {
            echo "\033[" . $color . $data  . "\033[0m \n";
        } else {
            var_dump($data);
        }
        return true;
    }

    /**
     * Logs to shop owners folder in /logs directory
     *
     * @param string $content  Content
     * @param string $filename File name
     *
     * @return $this
     */
    public function log( $content , $filename = 'log' )
    {
        $dir = $this->basePath() . 'logs'  .
            DIRECTORY_SEPARATOR . $this->shop() . DIRECTORY_SEPARATOR ;

        if (! is_dir($dir)) {

            mkdir($dir, 0775, true);

            $htaccess = $dir . DIRECTORY_SEPARATOR . '.htaccess' ;
            $file     = fopen($htaccess,'w') ;

            fwrite($file, "deny from all");
            fclose($file);

        }

        Logger::writeToLogFile( $content , $filename , $this->shop() , $dir , $this->timezone ) ;

        return $this;
    }

    /**
     * @param null $data
     * @return $this|bool
     */
    public function processing( $data = null ){
        if( is_null( $data ) ) return $this->isProcessing  ;

        $this->isProcessing = $data ;

        return $this;
    }

    /**
     * Checks if shop owner's app is in test mode.
     *
     * @param bool $data
     * @return $this|bool
     */
    public function testMode( $data = null )
    {
        if( is_null( $data ) ) return ( bool ) $this->testMode;

        $this->testMode = ( bool ) $data;

        return $this;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     * @return AbstractCommand
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * @param $error
     * @return $this
     */
    public function addError( $error ){
        $errors = $this->getErrors();
        $errors[] = $error;
        $this->setErrors( $errors ) ;
        return $this;
    }

    /**
     * Get all keys / properties of an object
     *
     * @param $object
     * @return array
     */
    public function objectKeys( $object ){

        if( ! is_object( $object ) ) return [];

        $keys = [];

        foreach( get_object_vars( $object ) as $key => $value ){
            $keys[] = $key ;
        }

        return $keys;
    }

    public function shop(){
        if( isset( $this->shopOwner->shop ) ){
            return $this->shopOwner->shop;
        }
        return 'default';
    }

    public function removeCron()
    {
        try{
            $this->cron->delete();
        } catch (\Exception $exception){
            return false;
        }

        return true;
    }
}