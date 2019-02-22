<?php

namespace App\Console\Commands\Shop;

use App\Console\Commands\AbstractCommand;
use App\Model\Cron;
use App\Manager\Shopify\Webhook as ShopifyWebhook;

class Webhook extends AbstractCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shop:webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create webhooks for new stores';

    /**
     * The list of webhooks to create in Shopify.
     *
     * @var array
     */
    protected $webhooks = [
        'app/uninstalled' ,
        'products/create', 'products/delete', 'products/update' ,
        'locations/create', 'locations/delete', 'locations/update' ,
        'inventory_items/create', 'inventory_items/delete', 'inventory_items/update' ,
        'inventory_levels/connect', 'inventory_levels/update', 'inventory_levels/disconnect'
    ];

    /**
     * Webhook constructor.
     *
     * Create a new command instance
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
        $crons = Cron::where( [ ['status' , '=' , Cron::QUEUED ] , [ 'type' , '=' , Cron::TYPE_WEBHOOK ]  ] )->get();

        if( ! isset( $crons ) ) return false;

        foreach( $crons as $cron ){

            $this->shop = $cron->shopOwner->name ;

            foreach( $this->webhooks as $webhook ){

                $shopifyWebhook = new ShopifyWebhook();

                $shopifyWebhook->setAccessToken( $cron->shopOwner->token )->setShop( $cron->shopOwner->name );

                $result = $shopifyWebhook->setTopic( $webhook )
                    ->setFormat( 'json' )
                    ->setAddress( env( 'APP_URL' ) . 'webhooks' )
                    ->save();

                if( $result === false || ( is_object( $result ) && property_exists( $result , 'errors' ) ) ) {

                    $this->info( 'Error creating webhook: ' . $webhook . ' ' . json_encode( $result ) );

                    $cron->update( [ 'status' => Cron::ERROR ] );

                }
                else {

                    $this->info( "$webhook webhook successfully created.");
                    $cron->delete();
                }

            }
        }
    }
}