<?php

namespace App\Service;

use App\DTO\Email;
use App\DTO\Event;

final class EmailParser
{
	public function parse(Email $email): Event
	{
		dump($email);
		preg_match('~\*Událost č\. (?<id>\d+) - odbavil (?<name>.+) - (?<time>\d{1,2}\.\d{1,2}\.\d{4} \d{1,2}:\d{2}:\d{2})\*~', $email->plain, $footer);

		return new Event(
			id: null,
			emailDate: $email->date,
			emailSubject: $email->subject,
			izscrId: $footer['id'] ?? null,
			izscrDate: $footer['name'] ?? null,
			izscrName: $footer['time'] ?? null,
			title: null,
			object: null,
			clarification: null,
			description: null,
			vehiclesLocal: null,
			vehiclesOther: null,
			reporterName: null,
			reporterPhone: null,
			city: null,
			cityPart: null,
			street: null,
			region: null,
			zip: null,
			lat: null,
			lng: null,
			createdAt: null,
		);
	}
}
