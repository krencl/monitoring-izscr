<?php

namespace App\Service;

use App\DTO\Event;
use DateTime;

final class EventsResolver
{
	public function __construct(
		private readonly ImapChecker $imapChecker,
		private readonly EmailParser $emailParser,
		private readonly Db $db,
	) {
	}

	public function updateNewEvents(): void
	{
		$lastDate = $this->db->getDibiConnection()
			->select('MAX(created_at)')
			->from('event')
			->fetchSingle();

		$dateSince = $lastDate ?? new DateTime('2023-03-01 00:00:00');
		$imapEmails = $this->imapChecker->getEmails($dateSince);
		$events = array_map($this->emailParser->parse(...), $imapEmails);
		$this->saveEvents(array_reverse($events));
	}

	/** @param array<Event> $events */
	private function saveEvents(array $events)
	{
		$connection = $this->db->getDibiConnection();
		foreach ($events as $event) {
			$connection->insert('event', $event->toDbArray())
				->setFlag('IGNORE')
				->execute();
		}
	}

	/** @return array<Event> */
	public function getLastEvents(int $limit, int $offset): array
	{
		$rows = $this->db->getDibiConnection()
			->select('*')
			->from('event')
			->orderBy('email_date DESC')
			->limit($limit)
			->offset($offset)
			->execute()
			->fetchAll();

		return array_map(Event::fromDibiRow(...), $rows);
	}

}
