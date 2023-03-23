<?php


namespace Application\Model;



use Application\Classes\Payment;

class PayPalModel
{
    protected $paypal;
    
    public function __construct()
    {
        try {
            $this->paypal = new Payment();
            
            $this->paypal->handlePayment();
            
        } catch (\PayPal\Exception\PayPalConnectionException $e) {
            echo $e->getData();
        }
    }
}