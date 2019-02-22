<?php
/**
 * Created by PhpStorm.
 * User: nate
 * Date: 2/20/19
 * Time: 8:55 AM
 */

namespace App\Console\Commands\Export;


use App\Console\Commands\AbstractCommand;

class Shopify extends AbstractCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:shopify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export data from Shopify';

    /**
     * Shopify constructor.
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