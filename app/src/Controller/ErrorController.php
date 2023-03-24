<?php

namespace App\Controller;

use App\Service\Config;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class ErrorController extends AbstractController implements ControllerInterface
{
	public function __construct(private readonly Environment $twig, Config $config)
	{
		parent::__construct($config);
	}

	public function getResponse(Request $request): Response
	{
		return new Response($this->twig->render('error.html.twig', [
			'title' => $this->config->get('title'),
		]), Response::HTTP_NOT_FOUND);
	}
}
