<?php

namespace App\Controller;

use App\Service\Config;

abstract class AbstractController
{
	public function __construct(protected readonly Config $config)
	{
	}
}
