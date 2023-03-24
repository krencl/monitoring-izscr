<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class EventsController extends AbstractController implements ControllerInterface
{
	public function getResponse(Request $request): Response
	{
		return new JsonResponse();
	}
}
