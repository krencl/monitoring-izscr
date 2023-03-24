<?php

namespace App\Service;

use Dibi\Connection;
use Dibi\Exception as DibiException;

final class Db
{
	private Connection $connection;

	public function __construct(private readonly Config $config)
	{
	}

	/** @throws DibiException */
	public function getDibiConnection(bool $skipDatabase = false): Connection
	{
		$this->connection ??= new Connection([
			'host' => $this->config->get('db.host'),
			'user' => $this->config->get('db.user'),
			'pass' => $this->config->get('db.pass'),
			'database' => $skipDatabase ? null : $this->config->get('db.database'),
		]);

		return $this->connection;
	}

	public function importSchema(string $templateFile): void
	{
		$file = $templateFile . '.tmp';
		$dbSchema = file_get_contents($templateFile);
		$dbSchema = str_replace('{{DATABASE_NAME}}', $this->config->get('db.database'), $dbSchema);
		file_put_contents($file, $dbSchema);

		$this->getDibiConnection()->loadFile($file);
		unlink($file);
	}
}
