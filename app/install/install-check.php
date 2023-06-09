<?php

use App\Service\Config;
use App\Service\Installer;
use Symfony\Component\ErrorHandler\Debug;

require __DIR__ . '/../init.php';

Debug::enable();

$installer = new Installer(
	config: new Config(),
	configFile: __DIR__ . '/../config.json',
);

if (!$installer->isConfigured()) {
	if (!$installer->setUpConfigFile(__DIR__ . '/../config.template.json')) {
		exit(1);
	}
}
if (!$installer->checkEmailConnection()) {
	exit(1);
}
if (!$installer->setUpDatabase(__DIR__ . '/schema.template.sql')) {
	exit(1);
}

echo 'Instalace dokoncena' . PHP_EOL;
