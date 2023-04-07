<?php

namespace App\Service;

use App\DTO\Email;
use App\DTO\Event;
use DateTime;

final class EmailParser
{
	public function parse(Email $email): Event
	{
		$html = $this->prettifyRaw($email->html);

		preg_match('~Blovice.+<b><big>(?<title>[^<]+)</big>~Uis', $html, $title);
		preg_match('~(?<lat>\d{2}\.\d{4,8}) N, (?<lng>\d{2}\.\d{4,8}) E~', $html, $gps);
		preg_match('~OZNÁMIL:.+<b>(?<name>[^<]+)</b>.*Telefon:.+<b>(?<phone>[^<]*)</b>~Ui', $html, $reporter);
		preg_match('~Událost.+ (?<id>\d+)~Ui', $html, $id);
		preg_match('~odbavil (?<name>.+) - (?<time>\d{1,2}\.\d{1,2}\.\d{4} \d{1,2}:\d{2}:\d{2}).+</small>~Ui', $html, $footer);

		return new Event(
			id: null,
			emailId: $email->id,
			emailDate: $email->date->format(Event::DateFormat),
			emailSubject: $email->subject,
			izscrId: $this->getMatched($id, 'id'),
			izscrDate: $this->getDateTime($this->getMatched($footer, 'time')),
			izscrName: $this->getMatched($footer, 'name'),
			title: $this->getMatched($title, 'title'),
			object: $this->matchBig($html, 'OBJEKT'),
			clarification: $this->matchBig($html, 'UPŘESNĚNÍ'),
			description: $this->matchBig($html, 'CO SE STALO'),
			vehiclesLocal: $this->matchVehicles($html, 'TECHNIKA Blovice'),
			vehiclesOther: $this->matchVehicles($html, 'TECHNIKA dalších jednotek PO'),
			reporterName: $this->getMatched($reporter, 'name'),
			reporterPhone: $this->getMatched($reporter, 'phone'),
			city: $this->matchBig($html, 'OBEC'),
			cityPart: $this->matchBig($html, 'ČÁST'),
			street: $this->matchBig($html, 'ULICE'),
			streetNumber: $this->matchBig($html, 'Č.P.'),
			region: $this->matchBig($html, 'KRAJ'),
			zip: $this->matchBig($html, 'PSČ'),
			lat: (float) $this->getMatched($gps, 'lat') ?: null,
			lng: (float) $this->getMatched($gps, 'lng') ?: null,
			createdAt: null,
		);
	}

	private function prettifyRaw(string $text): string
	{
		return str_replace(["\r", "\xC2\xA0"], ["", " "], $text);
	}

	private function getMatched(array $footer, string $key): string|null
	{
		return trim($footer[$key] ?? '') ?: null;
	}

	private function getDateTime(string|null $value, string $format = 'd.m.Y H:i:s'): string|null
	{
		if (!$value) {
			return null;
		}

		return DateTime::createFromFormat($format, $value)->format(Event::DateFormat) ?: null;
	}

	private function matchBig(string $haystack, string $title): string|null
	{
		if (!preg_match('~' . preg_quote($title, '~') . ':.+<big>(?<value>[^<]+)</big>~Ui', $haystack, $match)) {
			return null;
		}

		return trim($match['value']) ?: null;
	}

	private function matchVehicles(string $haystack, string $title): string|null
	{
		if (!preg_match('~' . preg_quote($title, '~') . ':.+<big>(?<value>.+)</big>~Uis', $haystack, $match)) {
			return null;
		}

		$vehicles = trim(strip_tags(str_replace('<br>', "\n", $match['value'])));

		if (preg_match('~^[ :-]*$~', $vehicles)) {
			return null;
		}

		return $vehicles;
	}
}
