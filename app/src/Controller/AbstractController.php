<?php

namespace App\Controller;

use App\Service\Config;
use App\Service\Db;

abstract class AbstractController
{
	public function __construct(protected readonly Config $config, protected readonly Db $db)
	{
	}
}
