<?php

	namespace Application\Classes;
	
	
	use Paypal\Rest\ApiContext;
	use Paypal\Auth\OAuthTokenCredential;
	use Paypal\Api\Payer;
	use Paypal\Api\Amount;
	use Paypal\Api\Transaction;
	use Paypal\Api\RedirectUrls;
	use Paypal\Api\Payment as PayPalPayment;
	
	class Payment
	{
		private $api_info;
		
		
		public function __construct()
		{
			$this->api_info = new ApiContext(
				new OAuthTokenCredential('ASVIfc-h7_1v2ii1FAXRRYXvNFT0f5SO5G6PYgnWSTm2QEfKXjJC70lvy5Z14WPs203hDnYmIAAe3OxX',
					'EAxskD73-DTbhEAhT-qPOwKIyi7xf__bZyeXonnN0q_Y2o0t9aTPDJ6QO2PvFIBu2Fpksrzkfe1k7__p'));
		}
		
		
		public function handlePayment()
		{
			$payer = new Payer();
			$payer->setPaymentMethod('paypal');
			
			$amount = new Amount();
			$amount->setTotal('1.00');
			$amount->setCurrency('USD');
			
			
			$transaction = new Transaction();
			$transaction->setAmount($amount);
			
			$redirect = new RedirectUrls();
			$redirect->setReturnUrl('/success')->setCancelUrl('/cancel');
			
			$payment = new Payment();
			$payment->setIntent('sale')
				->setPayer($payer)
				->setTransactions(array($transaction))
				->setRedirectUrls($redirect);
			
			$payment->create($this->api_info);
			
			return $payment;
		}
	}