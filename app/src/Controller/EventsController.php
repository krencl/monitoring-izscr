<?php

namespace App\Controller;

use App\DTO\Email;
use App\DTO\Event;
use App\Service\Config;
use App\Service\Db;
use App\Service\EmailParser;
use App\Service\ImapChecker;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class EventsController extends AbstractController implements ControllerInterface
{
	public function __construct(
		private readonly ImapChecker $imapChecker,
		private readonly EmailParser $emailParser,
		Config $config,
		Db $db
	) {
		parent::__construct($config, $db);
	}

	public function getResponse(Request $request): Response
	{
		$lastDate = $this->db->getDibiConnection()->select('MAX(created_at)')
			->from('event')
			->fetchSingle();

		$dateSince = $lastDate ?? new DateTime('2023-03-01 00:00:00');

		//$imapEmails = $this->imapChecker->getEmails($dateSince);
		$imapEmails = unserialize(file_get_contents(__DIR__ . '/../../tmp/email-mock.tmp'));

		$events = array_map($this->emailParser->parse(...), $imapEmails);

		dump($events);
		return new JsonResponse();
	}
}
