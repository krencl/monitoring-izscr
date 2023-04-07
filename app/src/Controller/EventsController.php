<?php

namespace App\Controller;

use App\Service\Config;
use App\Service\EventsResolver;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class EventsController extends AbstractController implements ControllerInterface
{
	private const DEFAULT_LIMIT = 10;
	private const DEFAULT_OFFSET = 0;

	public function __construct(
		private readonly EventsResolver $eventsResolver,
		Config $config,
	) {
		parent::__construct($config);
	}

	public function getResponse(Request $request): Response
	{
		$limit = (int) $request->query->get('limit', self::DEFAULT_LIMIT);
		$offset = (int) $request->query->get('offset', self::DEFAULT_OFFSET);

		$this->eventsResolver->updateNewEvents();

		$events = $this->eventsResolver->getLastEvents($limit, $offset);

		return new JsonResponse($events);
	}
}
