<?php

use App\Service\Config;
use App\Service\Db;
use Symfony\Component\ErrorHandler\Debug;

require __DIR__ . '/../vendor/autoload.php';

Debug::enable();

$config = new Config();
$configFile = __DIR__ . '/../config.json';
$configFileTemplate = __DIR__ . '/../config.template.json';

function info(string $text, bool $eol = true): void
{
	echo $text . ($eol ? PHP_EOL : '');
}


info('Kontroluji konfiguraci...', false);
if (!file_exists($configFile)) {
	info('NEEXISTUJE');
	$configTemplate = json_decode(file_get_contents($configFileTemplate), true);

	$configTemplate['email']['imapServer'] = readline('Zadej email imap server [default: ' . $configTemplate['email']['imapServer'] . ']: ') ?: $configTemplate['email']['imapServer'];
	$configTemplate['email']['user'] = readline('Zadej email login: ') ?: '';
	$configTemplate['email']['pass'] = readline('Zadej email heslo: ') ?: '';

	file_put_contents($configFile, json_encode($configTemplate, JSON_PRETTY_PRINT));

	info('Konfigurace vytvorena');
	$config->loadArray($configTemplate);
} else {
	info('OK');
	$config->loadFile($configFile);
}

info('Kontroluji databazi...', false);
$db = new Db($config);
$dbName = $config->get('db.database');

$dibi = $db->getDibiConnection(skipDatabase: true);
$result = $dibi->query('SHOW DATABASES LIKE %s', $dbName)->fetchAll();
if (empty($result)) {
	info('NEEXISTUJE');
	info('Vytvarim databazi...');
	$db->importSchema(__DIR__ . '/schema.template.sql');
	info('OK');
} else {
	info('OK');
}
