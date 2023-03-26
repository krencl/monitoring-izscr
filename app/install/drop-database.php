<?php

use App\Service\Config;
use App\Service\Installer;
use Symfony\Component\ErrorHandler\Debug;

require __DIR__ . '/../vendor/autoload.php';

Debug::enable();

$installer = new Installer(
	config: new Config(),
	configFile: __DIR__ . '/../config.json',
);

if (!$installer->isConfigured()) {
	exit(1);
}

$installer->dropDatabase();
