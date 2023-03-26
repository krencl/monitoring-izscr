<?php

namespace App\Controller;

use App\Service\Config;
use App\Service\Db;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class IndexController extends AbstractController implements ControllerInterface
{
	public function __construct(private readonly Environment $twig, Config $config, Db $db)
	{
		parent::__construct($config, $db);
	}

	public function getResponse(Request $request): Response
	{
		return new Response($this->twig->render('index.html.twig', [
			'title' => $this->config->get('title'),
		]));
	}
}
