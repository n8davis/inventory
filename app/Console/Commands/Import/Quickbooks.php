<?php
/**
 * Created by PhpStorm.
 * User: nate
 * Date: 2/20/19
 * Time: 8:55 AM
 */

namespace App\Console\Commands\Import;


use App\Console\Commands\AbstractCommand;

class Quickbooks extends AbstractCommand

{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:quickbooks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data to Quickbooks';

    /**
     * Quickbooks constructor.
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

    }

}