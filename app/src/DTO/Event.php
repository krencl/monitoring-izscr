<?php

namespace App\DTO;

use DateTime;
use Dibi\Row;

final class Event
{
	public const DateFormat = 'j.n.Y H:i:s';

	public function __construct(
		public readonly int|null $id,
		public readonly string $emailId,
		public readonly string $emailDate,
		public readonly string $emailSubject,
		public readonly string|null $izscrId = null,
		public readonly string|null $izscrDate = null,
		public readonly string|null $izscrName = null,
		public readonly string|null $title = null,
		public readonly string|null $object = null,
		public readonly string|null $clarification = null,
		public readonly string|null $description = null,
		public readonly string|null $vehiclesLocal = null,
		public readonly string|null $vehiclesOther = null,
		public readonly string|null $reporterName = null,
		public readonly string|null $reporterPhone = null,
		public readonly string|null $city = null,
		public readonly string|null $cityPart = null,
		public readonly string|null $street = null,
		public readonly string|null $streetNumber = null,
		public readonly string|null $region = null,
		public readonly string|null $zip = null,
		public readonly float|null $lat = null,
		public readonly float|null $lng = null,
		public readonly string|null $createdAt = null,
	) {
	}

	public function toDbArray(): array
	{
		return [
			'id' => $this->id,
			'email_id' => $this->emailId,
			'email_date' => DateTime::createFromFormat(self::DateFormat, $this->emailDate),
			'email_subject' => $this->emailSubject,
			'izscr_id' => $this->izscrId,
			'izscr_date' => $this->izscrDate ? DateTime::createFromFormat(self::DateFormat,$this->izscrDate) : null,
			'izscr_name' => $this->izscrName,
			'title' => $this->title,
			'object' => $this->object,
			'clarification' => $this->clarification,
			'description' => $this->description,
			'vehicles_local' => $this->vehiclesLocal,
			'vehicles_other' => $this->vehiclesOther,
			'reporter_name' => $this->reporterName,
			'reporter_phone' => $this->reporterPhone,
			'city' => $this->city,
			'city_part' => $this->cityPart,
			'street' => $this->street,
			'street_number' => $this->streetNumber,
			'region' => $this->region,
			'zip' => $this->zip,
			'lat' => $this->lat,
			'lng' => $this->lng,
			'created_at' => $this->createdAt ? DateTime::createFromFormat(self::DateFormat,$this->createdAt) : new DateTime(),
		];
	}

	public static function fromDibiRow(Row $row): self
	{
		return new self(
			$row->id,
			$row->email_id,
			$row->email_date->format(self::DateFormat),
			$row->email_subject,
			$row->izscr_id,
			$row->izscr_date?->format(self::DateFormat),
			$row->izscr_name,
			$row->title,
			$row->object,
			$row->clarification,
			$row->description,
			$row->vehicles_local,
			$row->vehicles_other,
			$row->reporter_name,
			$row->reporter_phone,
			$row->city,
			$row->city_part,
			$row->street,
			$row->street_number,
			$row->region,
			$row->zip,
			$row->lat,
			$row->lng,
			$row->created_at->format(self::DateFormat),
		);
	}
}
