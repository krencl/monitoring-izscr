<?php

namespace App\DTO;

use DateTime;
use Dibi\Row;

final class Event
{
	public function __construct(
		public readonly int|null $id,
		public readonly DateTime $emailDate,
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

	public static function fromDibiRow(Row $row): self
	{
		return new self(
			$row->id,
			$row->email_date,
			$row->email_subject,
			$row->icscr_id,
			$row->izscr_date,
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
			$row->region,
			$row->zip,
			$row->lat,
			$row->lng,
			$row->created_at,
		);
	}
}
