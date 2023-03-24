<?php

namespace App\Service;

use App\Exception\ConfigException;

final class Config
{
	/** @var array<string, mixed> */
	private array $config = [];

	public function loadArray(array $config): void
	{
		$this->config = $this->flattenConfig($config);
	}

	/**
	 * @throws ConfigException
	 */
	public function loadFile(string $filename): void
	{
		if (!file_exists($filename)) {
			throw new ConfigException('Config file not found');
		}

		$rawContent = json_decode(file_get_contents($filename), true, 32);
		if (!is_array($rawContent)) {
			throw new ConfigException('Invalid config JSON');
		}

		$this->config = $this->flattenConfig($rawContent);
	}

	public function get(string $name): string|int|null
	{
		return $this->config[$name] ?? null;
	}

	private function flattenConfig(array $rawContent, array $content = [], string $parentKey = ''): array
	{
		foreach ($rawContent as $key => $value) {
			$currentKey = ltrim($parentKey . '.' . $key, '.');
			if (is_array($value)) {
				$content = array_merge($content, $this->flattenConfig($value, $content, $currentKey));
			} else {
				$content[$currentKey] = $value;
			}
		}

		return $content;
	}
}
