<?php
namespace App\Manager\Shopify\Order;


class PaymentDetail
{


    protected $avs_result_code;
    protected $credit_card_bin;
    protected $cvv_result_code;
    protected $credit_card_number;
    protected $credit_card_company;

    /**
     * @return mixed
     */
    public function getAvsResultCode()
    {
        return $this->avs_result_code;
    }

    /**
     * @param mixed $avs_result_code
     * @return PaymentDetail
     */
    public function setAvsResultCode($avs_result_code)
    {
        $this->avs_result_code = $avs_result_code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreditCardBin()
    {
        return $this->credit_card_bin;
    }

    /**
     * @param mixed $credit_card_bin
     * @return PaymentDetail
     */
    public function setCreditCardBin($credit_card_bin)
    {
        $this->credit_card_bin = $credit_card_bin;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCvvResultCode()
    {
        return $this->cvv_result_code;
    }

    /**
     * @param mixed $cvv_result_code
     * @return PaymentDetail
     */
    public function setCvvResultCode($cvv_result_code)
    {
        $this->cvv_result_code = $cvv_result_code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreditCardNumber()
    {
        return $this->credit_card_number;
    }

    /**
     * @param mixed $credit_card_number
     * @return PaymentDetail
     */
    public function setCreditCardNumber($credit_card_number)
    {
        $this->credit_card_number = $credit_card_number;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreditCardCompany()
    {
        return $this->credit_card_company;
    }

    /**
     * @param mixed $credit_card_company
     * @return PaymentDetail
     */
    public function setCreditCardCompany($credit_card_company)
    {
        $this->credit_card_company = $credit_card_company;
        return $this;
    }

}