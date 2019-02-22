<?php

namespace App\Manager\Shopify;

use App\Manager\Shopify\Order\BillingAddress;
use App\Manager\Shopify\Order\ClientDetail;
use App\Manager\Shopify\Order\DiscountApplication;
use App\Manager\Shopify\Order\DiscountCode;
use App\Manager\Shopify\Order\Fulfillment;
use App\Manager\Shopify\Order\LineItem;
use App\Manager\Shopify\Order\NoteAttribute;
use App\Manager\Shopify\Order\Refund;
use App\Manager\Shopify\Order\ShippingAddress;
use App\Manager\Shopify\Order\ShippingLine;
use App\Manager\Shopify\Order\SubtotalPriceSet;
use App\Manager\Shopify\Order\TaxLine;
use App\Manager\Shopify\Order\Transaction;

class Order extends Shopify
{

    const SINGULAR_NAME = 'order';
    const PLURAL_NAME   = 'orders';

    protected $billing_address;
    protected $browser_ip;
    protected $buyer_accepts_marketing;
    protected $cancel_reason;
    protected $cancelled_at;
    protected $cart_token;
    protected $client_details;
    protected $closed_at;
    protected $created_at;
    protected $currency;
    protected $customer;
    protected $discount_codes = [];
    protected $email;
    protected $financial_status;
    protected $fulfillments = [];
    protected $fulfillment_status;
    protected $tags;
    protected $gateway;
    protected $test;
    protected $id;
    protected $landing_site;
    protected $line_items = [];
    protected $location_id;
    protected $name;
    protected $note;
    protected $note_attributes;
    protected $number;
    protected $order_number;
    protected $payment_details;
    protected $payment_gateway_names;
    protected $processed_at;
    protected $processing_method;
    protected $referring_site;
    protected $refunds;
    protected $shipping_address;
    protected $shipping_lines = [];
    protected $source_name;
    protected $subtotal_price;
    protected $tax_lines = [];
    protected $taxes_included;
    protected $token;
    protected $total_discounts;
    protected $total_line_items_price;
    protected $total_price;
    protected $total_tax;
    protected $total_weight;
    protected $updated_at;
    protected $user_id;
    protected $order_status_url;
    protected $send_receipt = false;
    protected $send_fulfillment_receipt = false;
    protected $transactions = [];
    protected $order_name;
    protected $phone;
    protected $confirmed;
    protected $total_price_usd;
    protected $checkout_token;
    protected $reference;
    protected $source_identifier;
    protected $source_url;
    protected $device_id;
    protected $customer_locale;
    protected $app_id;
    protected $landing_site_ref;
    protected $checkout_id;
    protected $discount_applications;
    protected $contact_email;
    protected $total_tip_received;
    protected $admin_graphql_api_id;
    protected $subtotal_price_set;

    public function process( $order )
    {
        if( ! is_object( $order ) ) return null;

        if( property_exists( $order , 'order' ) ) $order = $order->order;

        $billing = new BillingAddress();
        $shipping = new ShippingAddress();
        $clientDetail = new ClientDetail();
        $customer = new Order\Customer();
        $discountApplication = new Order\DiscountApplication();
        $discountCode = new Order\DiscountCode();
        $fulfillment = new Order\Fulfillment();
        $lineItem = new Order\LineItem();
        $noteAttribute = new Order\NoteAttribute();
        $transaction = new Order\Transaction();
        $refund = new Order\Refund();
        $shippingLine = new Order\ShippingLine();
        $taxLine      = new Order\TaxLine();

        $discountCodes  = $this->getProperty( $order , 'discount_codes' );
        $fulfillments   = $this->getProperty( $order , 'fulfillments' ) ;
        $lineItems      = $this->getProperty( $order , 'line_items' ) ;
        $noteAttributes = $this->getProperty( $order , 'note_attributes' ) ;
        $refunds        = $this->getProperty( $order , 'refunds' ) ;
        $shippingLines  = $this->getProperty( $order , 'shipping_lines' ) ;
        $taxLines       = $this->getProperty( $order , 'tax_lines' ) ;
        $transactions   = $this->getProperty( $order , 'transactions' ) ;

        if( ! empty( $transactions ) ) {
            foreach ($transactions as $value) {
                $this->addTransaction($transaction->setup($value));
            }
        }

        if( ! empty( $taxLines ) ) {
            foreach ($taxLines as $value) {
                $this->addTaxLine($taxLine->setup($value));
            }
        }

        if( ! empty( $shippingLines ) ) {
            foreach ($shippingLines as $value) {
                $this->addShippingLine($shippingLine->setup($value));
            }
        }

        if( ! empty( $refunds ) ) {
            foreach ($refunds as $value) {
                $this->addRefund($refund->setup($value));
            }
        }

        if( ! empty( $noteAttributes ) ) {
            foreach ($noteAttributes as $value) {
                $this->addNoteAttribute($noteAttribute->setup($value));
            }
        }

        if( ! empty( $lineItems ) ) {
            foreach ($lineItems as $value) {
                $this->addLineItem($lineItem->setup($value));
            }
        }

        if( ! empty( $fulfillments ) ) {
            foreach ($fulfillments as $value) {
                $this->addFulfillment($fulfillment->setup($value));
            }
        }

        if( ! empty( $discountCodes ) ) {
            foreach ($discountCodes as $value) {
                $this->addDiscountCode($discountCode->setup($value));
            }
        }

        $this->setBillingAddress( $billing->setup(  $this->getProperty( $order , 'billing_address' ) ) )
            ->setShippingAddress( $shipping->setup(  $this->getProperty( $order , 'shipping_address' ) ) )
            ->setDiscountApplications( $discountApplication->setup( $this->getProperty( $order , 'discount_application' ) ) )
            ->setBrowserIp( $this->getProperty( $order , 'browser_ip' ) )
            ->setBuyerAcceptsMarketing( $this->getProperty( $order , 'buyer_accepts_marketing' ) )
            ->setCancelledAt( $this->getProperty( $order , 'cancelled_at' ) )
            ->setCancelReason( $this->getProperty( $order , 'cancel_reason' ) )
            ->setCartToken( $this->getProperty( $order , 'cart_token' ) )
            ->setClientDetails( $clientDetail->setup( $this->getProperty( $order , 'client_details' ) ) )
            ->setClosedAt( $this->getProperty( $order , 'closed_at' ) )
            ->setCreatedAt( $this->getProperty( $order , 'created_at' ) )
            ->setCurrency( $this->getProperty( $order , 'currency' ) )
            ->setCustomer( $customer->setup( $this->getProperty( $order , 'customer' ) ) )
            ->setCustomerLocale( $this->getProperty( $order , 'customer_locale' ) )
            ->setEmail( $this->getProperty( $order , 'email' ) )
            ->setFinancialStatus( $this->getProperty( $order , 'financial_status' ) )
            ->setFulfillmentStatus( $this->getProperty( $order , 'fulfillment_status' ) )
            ->setTags( $this->getProperty( $order , 'tags' ) )
            ->setGateway( $this->getProperty( $order , 'gateway' ) )
            ->setTest( $this->getProperty( $order , 'test' ) )
            ->setId( $this->getProperty( $order , 'id' ) )
            ->setLandingSite( $this->getProperty( $order , 'landing_site' ) )
            ->setLocationId( $this->getProperty( $order , 'location_id' ) )
            ->setName( $this->getProperty( $order , 'name' ) )
            ->setNote( $this->getProperty( $order , 'note' ) )
            ->setNumber( $this->getProperty( $order , 'number' ) )
            ->setOrderNumber( $this->getProperty( $order , 'order_number' ) )
            ->setPaymentDetails( $this->getProperty( $order , 'payment_details' ) )
            ->setPaymentGatewayNames( $this->getProperty( $order , 'payment_gateway_names' ) )
            ->setProcessedAt( $this->getProperty( $order , 'processed_at' ) )
            ->setProcessingMethod( $this->getProperty( $order , 'processing_method' ) )
            ->setReference( $this->getProperty( $order , 'reference' ) )
            ->setReferringSite( $this->getProperty( $order , 'referring_site' ) )
            ->setSourceIdentifier( $this->getProperty( $order , 'source_identifier' ) )
            ->setSourceName( $this->getProperty( $order , 'source_name' ) )
            ->setSubtotalPrice( $this->getProperty( $order , 'subtotal_price' ) )
            ->setTaxesIncluded( $this->getProperty( $order , 'taxes_included' ) )
            ->setToken( $this->getProperty( $order , 'token' ) )
            ->setTotalDiscounts( $this->getProperty( $order , 'total_discounts' ) )
            ->setTotalLineItemsPrice( $this->getProperty( $order , 'total_line_items_price' ) )
            ->setTotalPrice( $this->getProperty( $order , 'total_price' ) )
            ->setTotalTax( $this->getProperty( $order , 'total_tax' ) )
            ->setTotalWeight( $this->getProperty( $order , 'total_weight' ) )
            ->setUpdatedAt( $this->getProperty( $order , 'updated_at' ) );

        return $this;
    }

    public function getSingularName()
    {
        return self::SINGULAR_NAME;
    }

    public function getPluralName()
    {
        return self::PLURAL_NAME;
    }

    /**
     * @return mixed
     */
    public function getAdminGraphqlApiId()
    {
        return $this->admin_graphql_api_id;
    }

    /**
     * @param mixed $admin_graphql_api_id
     * @return Order
     */
    public function setAdminGraphqlApiId($admin_graphql_api_id)
    {
        $this->admin_graphql_api_id = $admin_graphql_api_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContactEmail()
    {
        return $this->contact_email;
    }

    /**
     * @param mixed $contact_email
     * @return Order
     */
    public function setContactEmail($contact_email)
    {
        $this->contact_email = $contact_email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalTipReceived()
    {
        return $this->total_tip_received;
    }

    /**
     * @param mixed $total_tip_received
     * @return Order
     */
    public function setTotalTipReceived($total_tip_received)
    {
        $this->total_tip_received = $total_tip_received;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDiscountApplications()
    {
        return $this->discount_applications;
    }

    /**
     * @param DiscountApplication $discountApplication
     * @return $this
     */
    public function addDiscountApplication( DiscountApplication $discountApplication ){
        $discounts = $this->getDiscountApplications() ;
        $discounts[] = $discountApplication ;
        $this->setDiscountApplications( $discounts );

        return $this;
    }

    /**
     * @param mixed $discount_applications
     * @return Order
     */
    public function setDiscountApplications($discount_applications)
    {
        $this->discount_applications = $discount_applications;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomerLocale()
    {
        return $this->customer_locale;
    }

    /**
     * @param mixed $customer_locale
     * @return Order
     */
    public function setCustomerLocale($customer_locale)
    {
        $this->customer_locale = $customer_locale;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAppId()
    {
        return $this->app_id;
    }

    /**
     * @param mixed $app_id
     * @return Order
     */
    public function setAppId($app_id)
    {
        $this->app_id = $app_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLandingSiteRef()
    {
        return $this->landing_site_ref;
    }

    /**
     * @param mixed $landing_site_ref
     * @return Order
     */
    public function setLandingSiteRef($landing_site_ref)
    {
        $this->landing_site_ref = $landing_site_ref;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCheckoutId()
    {
        return $this->checkout_id;
    }

    /**
     * @param mixed $checkout_id
     * @return Order
     */
    public function setCheckoutId($checkout_id)
    {
        $this->checkout_id = $checkout_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeviceId()
    {
        return $this->device_id;
    }

    /**
     * @param mixed $device_id
     * @return Order
     */
    public function setDeviceId($device_id)
    {
        $this->device_id = $device_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSourceUrl()
    {
        return $this->source_url;
    }

    /**
     * @param mixed $source_url
     * @return Order
     */
    public function setSourceUrl($source_url)
    {
        $this->source_url = $source_url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSourceIdentifier()
    {
        return $this->source_identifier;
    }

    /**
     * @param mixed $source_identifier
     * @return Order
     */
    public function setSourceIdentifier($source_identifier)
    {
        $this->source_identifier = $source_identifier;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param mixed $reference
     * @return Order
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * @param mixed $confirmed
     * @return Order
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCheckoutToken()
    {
        return $this->checkout_token;
    }

    /**
     * @param mixed $checkout_token
     * @return Order
     */
    public function setCheckoutToken($checkout_token)
    {
        $this->checkout_token = $checkout_token;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalPriceUsd()
    {
        return $this->total_price_usd;
    }

    /**
     * @param mixed $total_price_usd
     * @return Order
     */
    public function setTotalPriceUsd($total_price_usd)
    {
        $this->total_price_usd = $total_price_usd;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBillingAddress()
    {
        return $this->billing_address;
    }

    /**
     * @param mixed $billing_address
     * @return Order
     */
    public function setBillingAddress($billing_address)
    {
        $this->billing_address = $billing_address;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBrowserIp()
    {
        return $this->browser_ip;
    }

    /**
     * @param mixed $browser_ip
     * @return Order
     */
    public function setBrowserIp($browser_ip)
    {
        $this->browser_ip = $browser_ip;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBuyerAcceptsMarketing()
    {
        return $this->buyer_accepts_marketing;
    }

    /**
     * @param mixed $buyer_accepts_marketing
     * @return Order
     */
    public function setBuyerAcceptsMarketing($buyer_accepts_marketing)
    {
        $this->buyer_accepts_marketing = $buyer_accepts_marketing;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCancelReason()
    {
        return $this->cancel_reason;
    }

    /**
     * @param mixed $cancel_reason
     * @return Order
     */
    public function setCancelReason($cancel_reason)
    {
        $this->cancel_reason = $cancel_reason;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCancelledAt()
    {
        return $this->cancelled_at;
    }

    /**
     * @param mixed $cancelled_at
     * @return Order
     */
    public function setCancelledAt($cancelled_at)
    {
        $this->cancelled_at = $cancelled_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCartToken()
    {
        return $this->cart_token;
    }

    /**
     * @param mixed $cart_token
     * @return Order
     */
    public function setCartToken($cart_token)
    {
        $this->cart_token = $cart_token;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClientDetails()
    {
        return $this->client_details;
    }

    /**
     * @param mixed $client_details
     * @return Order
     */
    public function setClientDetails($client_details)
    {
        $this->client_details = $client_details;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClosedAt()
    {
        return $this->closed_at;
    }

    /**
     * @param mixed $closed_at
     * @return Order
     */
    public function setClosedAt($closed_at)
    {
        $this->closed_at = $closed_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     * @return Order
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     * @return Order
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return \App\Manager\Shopify\Order\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param \App\Manager\Shopify\Order\Customer $customer
     * @return Order
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * @return array
     */
    public function getDiscountCodes() 
    {
        return $this->discount_codes;
    }

    /**
     * @param array $discount_codes
     * @return Order
     */
    public function setDiscountCodes($discount_codes)
    {
        $this->discount_codes = $discount_codes;
        return $this;
    }

    public function addDiscountCode( DiscountCode $discountCode ){
        $discounts = $this->getDiscountCodes() ;
        $discounts[] = $discountCode ;

        $this->setDiscountCodes( $discounts );

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return Order
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFinancialStatus()
    {
        return $this->financial_status;
    }

    /**
     * @param mixed $financial_status
     * @return Order
     */
    public function setFinancialStatus($financial_status)
    {
        $this->financial_status = $financial_status;
        return $this;
    }

    /**
     * @return array|Fulfillment[]
     */
    public function getFulfillments() 
    {
        return $this->fulfillments;
    }

    public function addFulfillment( Fulfillment $fulfillment )
    {
        $fulfillments = $this->getFulfillments();
        $fulfillments[] = $fulfillment;
        $this->setFulfillments($fulfillments ) ;
        return $this;
    }

    /**
     * @param array|Fulfillment[] $fulfillments
     * @return Order
     */
    public function setFulfillments($fulfillments)
    {
        $this->fulfillments = $fulfillments;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFulfillmentStatus()
    {
        return $this->fulfillment_status;
    }

    /**
     * @param mixed $fulfillment_status
     * @return Order
     */
    public function setFulfillmentStatus($fulfillment_status)
    {
        $this->fulfillment_status = $fulfillment_status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     * @return Order
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * @param mixed $gateway
     * @return Order
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Order
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLandingSite()
    {
        return $this->landing_site;
    }

    /**
     * @param mixed $landing_site
     * @return Order
     */
    public function setLandingSite($landing_site)
    {
        $this->landing_site = $landing_site;
        return $this;
    }

    /**
     * @return array|LineItem[]
     */
    public function getLineItems() 
    {
        return $this->line_items;
    }

    public function addLineItem( LineItem $lineItem )
    {
        $lineItems = $this->getLineItems();
        $lineItems[] = $lineItem;
        $this->setLineItems( $lineItems );
        return $this;
    }

    /**
     * @param array|LineItem[] $line_items
     * @return Order
     */
    public function setLineItems( $line_items)
    {
        $this->line_items = $line_items;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocationId()
    {
        return $this->location_id;
    }

    /**
     * @param mixed $location_id
     * @return Order
     */
    public function setLocationId($location_id)
    {
        $this->location_id = $location_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Order
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param mixed $note
     * @return Order
     */
    public function setNote($note)
    {
        $this->note = $note;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNoteAttributes()
    {
        return $this->note_attributes;
    }

    public function addNoteAttribute( NoteAttribute $noteAttribute )
    {
        $noteAttributes = $this->getNoteAttributes();
        $noteAttributes[] = $noteAttribute ;
        $this->setNoteAttributes( $noteAttributes );

        return $this;
    }

    /**
     * @param mixed $note_attributes
     * @return Order
     */
    public function setNoteAttributes($note_attributes)
    {
        $this->note_attributes = $note_attributes;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     * @return Order
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderNumber()
    {
        return $this->order_number;
    }

    /**
     * @param mixed $order_number
     * @return Order
     */
    public function setOrderNumber($order_number)
    {
        $this->order_number = $order_number;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaymentDetails()
    {
        return $this->payment_details;
    }

    /**
     * @param mixed $payment_details
     * @return Order
     */
    public function setPaymentDetails($payment_details)
    {
        $this->payment_details = $payment_details;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaymentGatewayNames()
    {
        return $this->payment_gateway_names;
    }

    /**
     * @param mixed $payment_gateway_names
     * @return Order
     */
    public function setPaymentGatewayNames($payment_gateway_names)
    {
        $this->payment_gateway_names = $payment_gateway_names;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProcessedAt()
    {
        return $this->processed_at;
    }

    /**
     * @param mixed $processed_at
     * @return Order
     */
    public function setProcessedAt($processed_at)
    {
        $this->processed_at = $processed_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProcessingMethod()
    {
        return $this->processing_method;
    }

    /**
     * @param mixed $processing_method
     * @return Order
     */
    public function setProcessingMethod($processing_method)
    {
        $this->processing_method = $processing_method;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReferringSite()
    {
        return $this->referring_site;
    }

    /**
     * @param mixed $referring_site
     * @return Order
     */
    public function setReferringSite($referring_site)
    {
        $this->referring_site = $referring_site;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRefunds()
    {
        return $this->refunds;
    }

    public function addRefund( Refund $refund )
    {
        $refunds = $this->getRefunds();
        $refunds[] = $refund;
        $this->setRefunds( $refunds ) ;
        return $this;
    }

    /**
     * @param mixed $refunds
     * @return Order
     */
    public function setRefunds($refunds)
    {
        $this->refunds = $refunds;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getShippingAddress()
    {
        return $this->shipping_address;
    }

    /**
     * @param mixed $shipping_address
     * @return Order
     */
    public function setShippingAddress($shipping_address)
    {
        $this->shipping_address = $shipping_address;
        return $this;
    }

    /**
     * @return array
     */
    public function getShippingLines() 
    {
        return $this->shipping_lines;
    }

    /**
     * @param ShippingLine $line
     * @return $this
     */
    public function addShippingLine( ShippingLine $line )
    {
        $lines = $this->getShippingLines();
        $lines[] = $line;
        $this->setShippingLines( $lines ) ;

        return $this;
    }

    /**
     * @param array $shipping_lines
     * @return Order
     */
    public function setShippingLines($shipping_lines)
    {
        $this->shipping_lines = $shipping_lines;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSourceName()
    {
        return $this->source_name;
    }

    /**
     * @param mixed $source_name
     * @return Order
     */
    public function setSourceName($source_name)
    {
        $this->source_name = $source_name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubtotalPrice()
    {
        return $this->subtotal_price;
    }

    /**
     * @param mixed $subtotal_price
     * @return Order
     */
    public function setSubtotalPrice($subtotal_price)
    {
        $this->subtotal_price = $subtotal_price;
        return $this;
    }

    /**
     * @return array
     */
    public function getTaxLines() 
    {
        return $this->tax_lines;
    }

    public function addTaxLine( TaxLine $taxLine )
    {
        $taxLines = $this->getTaxLines() ;
        $taxLines[] = $taxLine ;
        $this->setTaxLines($taxLines ) ;
        return $this;
    }

    /**
     * @param array $tax_lines
     * @return Order
     */
    public function setTaxLines($tax_lines)
    {
        $this->tax_lines = $tax_lines;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTaxesIncluded()
    {
        return $this->taxes_included;
    }

    /**
     * @param mixed $taxes_included
     * @return Order
     */
    public function setTaxesIncluded($taxes_included)
    {
        $this->taxes_included = $taxes_included;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     * @return Order
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalDiscounts()
    {
        return $this->total_discounts;
    }

    /**
     * @param mixed $total_discounts
     * @return Order
     */
    public function setTotalDiscounts($total_discounts)
    {
        $this->total_discounts = $total_discounts;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalLineItemsPrice()
    {
        return $this->total_line_items_price;
    }

    /**
     * @param mixed $total_line_items_price
     * @return Order
     */
    public function setTotalLineItemsPrice($total_line_items_price)
    {
        $this->total_line_items_price = $total_line_items_price;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalPrice()
    {
        return $this->total_price;
    }

    /**
     * @param mixed $total_price
     * @return Order
     */
    public function setTotalPrice($total_price)
    {
        $this->total_price = $total_price;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalTax()
    {
        return $this->total_tax;
    }

    /**
     * @param mixed $total_tax
     * @return Order
     */
    public function setTotalTax($total_tax)
    {
        $this->total_tax = $total_tax;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalWeight()
    {
        return $this->total_weight;
    }

    /**
     * @param mixed $total_weight
     * @return Order
     */
    public function setTotalWeight($total_weight)
    {
        $this->total_weight = $total_weight;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     * @return Order
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     * @return Order
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderStatusUrl()
    {
        return $this->order_status_url;
    }

    /**
     * @param mixed $order_status_url
     * @return Order
     */
    public function setOrderStatusUrl($order_status_url)
    {
        $this->order_status_url = $order_status_url;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSendReceipt(): bool
    {
        return $this->send_receipt;
    }

    /**
     * @param bool $send_receipt
     * @return Order
     */
    public function setSendReceipt(bool $send_receipt): Order
    {
        $this->send_receipt = $send_receipt;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSendFulfillmentReceipt(): bool
    {
        return $this->send_fulfillment_receipt;
    }

    /**
     * @param bool $send_fulfillment_receipt
     * @return Order
     */
    public function setSendFulfillmentReceipt(bool $send_fulfillment_receipt): Order
    {
        $this->send_fulfillment_receipt = $send_fulfillment_receipt;
        return $this;
    }

    /**
     * @return array
     */
    public function getTransactions() 
    {
        return $this->transactions;
    }

    public function addTransaction( Transaction $transaction )
    {
        $transactions = $this->getTransactions() ;
        $transactions[] = $transaction ;
        $this->setTransactions( $transactions );
        return $this;
    }

    /**
     * @param array $transactions
     * @return Order
     */
    public function setTransactions( $transactions)
    {
        $this->transactions = $transactions;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderName()
    {
        return $this->order_name;
    }

    /**
     * @param mixed $order_name
     * @return Order
     */
    public function setOrderName($order_name)
    {
        $this->order_name = $order_name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     * @return Order
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * @param mixed $shop
     * @return Order
     */
    public function setShop($shop)
    {
        $this->shop = $shop;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * @param mixed $access_token
     * @return Order
     */
    public function setAccessToken($access_token)
    {
        $this->access_token = $access_token;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * @param mixed $test
     * @return Order
     */
    public function setTest($test)
    {
        $this->test = $test;
        return $this;
    }

    /**
     * @return SubtotalPriceSet
     */
    public function getSubtotalPriceSet()
    {
        return $this->subtotal_price_set;
    }

    /**
     * @param SubtotalPriceSet $subtotal_price_set
     * @return Order
     */
    public function setSubtotalPriceSet( $subtotal_price_set)
    {
        $this->subtotal_price_set = $subtotal_price_set;
        return $this;
    }



}