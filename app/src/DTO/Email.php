<?php

namespace App\DTO;

use DateTimeInterface;

final class Email
{
	public function __construct(
		public readonly string $id,
		public readonly DateTimeInterface $date,
		public readonly string $subject,
		public readonly string $header,
		public readonly string $plain,
		public readonly string $html,
	) {
	}
}
