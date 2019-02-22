<?php

namespace App\Console\Commands\Shop;


use App\Console\Commands\AbstractCommand;
use App\Manager\Basic\Status;
use App\Model\Cron;
use App\Model\ShopOwner;

class LogCleaner extends AbstractCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shop:logCleaner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes logs per shop owners settings';

    /**
     * The type of file to look for during cleaning process
     *
     * @var string
     */
    protected $file_type = '.log';

    /**
     * LogCleaner constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        foreach( ShopOwner::all() as $shopOwner ){

            $this->shopOwner = $shopOwner;
            $this->shop      = $shopOwner->shop;

            if( ! isset( $shopOwner->configuration ) ) continue;

            $this->setup( $shopOwner->id );

            if( $this->processing() === false ) continue;

            $logRetention = null;
            foreach( $shopOwner->configuration as $configuration ){
                if( $configuration->entity === 'log_retention' ) {
                    $logRetention = $configuration->value;
                    break;
                }
            }

            $directory = $this->basePath()  . 'logs' . DIRECTORY_SEPARATOR . $shopOwner->shop ;
            $this->info( "Clean $directory every $logRetention days." ) ;

            $this->cleanLog( $directory , $logRetention ) ;
            try {
                $this->cron->delete();
            }
            catch ( \Exception $exception ){
                $this->info( $exception->getTraceAsString() ) ;
            }
        }

        return null;
    }

    public function cleanLog( $directory , $days = 30 )
    {
        try {
            $files = scandir($directory);

            if (empty($files)) return false;

            foreach ($files as $index => $file) {

                $this->info( "Looking at $directory/$file " ) ;

                if (!is_file($directory . DIRECTORY_SEPARATOR . $file) || $file[0] === '.') continue;

                $parseFileName = explode( $this->file_type , $file);

                if (!array_key_exists(0, $parseFileName)) continue;

                $findFileDate = substr($parseFileName[0], -8);

                $this->info( "File date: $findFileDate" );

                if ( \DateTime::createFromFormat('Ymd', $findFileDate) === false) continue;

                $fileDate = new \DateTime($findFileDate);
                $nowDate = new \DateTime();

                $this->info( "File is ".$fileDate->diff($nowDate)->days." days old" );
                if ( $fileDate->diff($nowDate)->days <= $days) continue;

                if( file_exists( $directory . DIRECTORY_SEPARATOR . $file  ) && ! is_dir( $directory . DIRECTORY_SEPARATOR . $file ) ) {
                    $this->info("Remove " . $directory . DIRECTORY_SEPARATOR . $file);
                    unlink($directory . DIRECTORY_SEPARATOR . $file);
                }
            }
            return true;
        }
        catch ( \Exception $exception ){
            return false;
        }
    }

}