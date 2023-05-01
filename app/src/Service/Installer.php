<?php

namespace App\Service;

use App\DTO\Event;
use DateTime;
use Faker\Factory as FakerFactory;

final class Installer
{
	public function __construct(
		private readonly Config $config,
		private readonly string $configFile,
	) {
	}

	public function isConfigured(): bool
	{
		$this->printInfo('Kontroluji konfiguraci...', false);
		if (!file_exists($this->configFile)) {
			$this->printInfo('NEEXISTUJE');
			return false;
		}

		$this->config->loadFile($this->configFile);
		$this->printInfo('OK');
		return true;
	}

	public function setUpConfigFile(string $configTemplateFile): bool
	{
		$this->printInfo('Vytvarim konfiguraci...');
		$configTemplate = json_decode(file_get_contents($configTemplateFile), true);

		$configTemplate['email']['host'] = readline('Zadej email imap server [default: ' . $configTemplate['email']['host'] . ']: ') ?: $configTemplate['email']['host'];
		$configTemplate['email']['port'] = readline('Zadej email imap port [default: ' . $configTemplate['email']['port'] . ']: ') ?: $configTemplate['email']['port'];
		$configTemplate['email']['user'] = readline('Zadej email login: ') ?: '';
		$configTemplate['email']['pass'] = readline('Zadej email heslo: ') ?: '';

		file_put_contents($this->configFile, json_encode($configTemplate, JSON_PRETTY_PRINT));
		$this->config->loadArray($configTemplate);
		$this->printInfo('OK');
		return true;
	}

	public function checkEmailConnection(): bool
	{
		$this->printInfo('Kontroluji pripojeni k emailu...', false);
		$imapChecker = new ImapChecker($this->config);
		if (!$imapChecker->connect()) {
			$this->printInfo('Nelze se pripojit na IMAP. Zkus zkontrolovat udaje v app/config.json.');
			return false;
		}

		$this->printInfo('OK');
		return true;
	}

	public function setUpDatabase(string $schemaFile): bool
	{
		$this->printInfo('Kontroluji databazi...', false);
		$db = new Db($this->config);
		$dbName = $this->config->get('db.database');

		$dibi = $db->getDibiConnection(skipDatabase: true);
		$result = $dibi->query('SHOW DATABASES LIKE %s', $dbName)->fetchAll();
		if (empty($result)) {
			$this->printInfo('NEEXISTUJE');
			$this->printInfo('Vytvarim databazi...', false);
			$db->importSchema($schemaFile);
		}

		$this->printInfo('OK');
		return true;
	}

	public function dropDatabase(): bool
	{
		$this->printInfo('Mazu databazi...', false);
		$db = new Db($this->config);
		$dbName = $this->config->get('db.database');

		$dibi = $db->getDibiConnection(skipDatabase: true);
		$dibi->query('DROP DATABASE %n', $dbName);

		$this->printInfo('OK');
		return true;
	}

	public function createTestingData(): void
	{
		$db = new Db($this->config);
		$dibi = $db->getDibiConnection();

		$faker = FakerFactory::create();
		$dibi->insert('event', (new Event(
			id: null,
			emailId: $faker->uuid(),
			emailDate: (new DateTime())->format(Event::DateFormat),
			emailSubject: 'Event ' . $faker->text(50),
			izscrId: $faker->numberBetween(10000, 99999),
			izscrDate: (new DateTime())->format(Event::DateFormat),
			izscrName: $faker->word() . ' ' . $faker->numberBetween(1, 9),
			title: $faker->realTextBetween(20, 40),
			object: $faker->realTextBetween(10, 30),
			clarification: $faker->realTextBetween(20, 50),
			description: $faker->realTextBetween(20, 100),
			vehiclesLocal: $faker->realTextBetween(10, 30) . "\n" . $faker->realTextBetween(10, 30),
			vehiclesOther: $faker->realTextBetween(10, 30),
			reporterName: $faker->name(),
			reporterPhone: $faker->phoneNumber(),
			city: $faker->city(),
			cityPart: $faker->streetName(),
			street: $faker->streetName(),
			streetNumber: $faker->numberBetween(1, 500),
			region: $faker->city(),
			zip: $faker->numberBetween(10000, 60000),
			lat: $faker->latitude(49, 51),
			lng: $faker->longitude(13, 18),
			createdAt: (new DateTime())->format(Event::DateFormat),
		))->toDbArray())->execute();

		$this->printInfo('Vytvoren event ID ' . $dibi->getInsertId(), false);
	}

	private function printInfo(string $text, bool $newLine = true): void
	{
		echo $text . ($newLine ? PHP_EOL : '');
	}
}
