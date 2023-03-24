<?php

namespace App\DTO;

use Closure;

final class Route
{
	public function __construct(public readonly string $path, public Closure $controller)
	{
	}
}
