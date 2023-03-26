<?php

namespace App\DTO;

use DateTime;

final class Email
{
	public function __construct(
		public readonly DateTime $date,
		public readonly string $subject,
		public readonly string $plain,
		public readonly string $html,
	) {
	}
}
