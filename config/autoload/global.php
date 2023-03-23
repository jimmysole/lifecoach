<?php

use Laminas\Db\Adapter;
use Laminas\Navigation;

return [
    'db' => [
    	'driver' => 'Pdo',
	    'dsn' => 'mysql:dbname=infamous_kb;host=localhost',
    ],
	
	'error_logging' => true,
	
	'service_manager' => [
		
		'factories' => [
			Adapter\Adapter::class => 	Adapter\AdapterServiceFactory::class,
			Navigation\Navigation::class => Navigation\Service\DefaultNavigationFactory::class,
			
			Laminas\Log\Logger::class => function ($container) {
				$logger = new Laminas\Log\Logger();
				
				$mapping = [
					'timestamp' => 'timestamp',
					'message'    => 'error_msg',
				];
				
				$writer = new Laminas\Log\Writer\Db($container->get(Adapter\Adapter::class), 'errors', $mapping);
				
				$writer->setFormatter(new Laminas\Log\Formatter\Db('Y-m-d H:i:s'));
				
				$logger->addWriter($writer);
				
				Laminas\Log\Logger::registerErrorHandler($logger);
				
				return $logger;
			},
		],
	],
	
	'session_config' => [
		'cookie_lifetime' => 60 * 60 * 1,
		'gc_maxlifetime' => 60 * 60 * 24 * 30,
		'name' => 'kevinbenitez-session',
	],
	
	'session_storage' => [
		'type' => Laminas\Session\Storage\SessionArrayStorage::class,
	],
	
	'session_validators' => [
		Laminas\Session\Validator\RemoteAddr::class,
		Laminas\Session\Validator\HttpUserAgent::class
	],
	
	'view_manager' => [
		'display_not_found_reasons' => true,
		'display_exceptions' => true
	]
];
