<?php

namespace App\Controllers;

use Slim\Psr7\Request;
use Slim\Psr7\Response;

class HomeController
{
  protected $renderer;
  protected $mailer;

  public function __construct(
    $renderer,
    $mailer
  ) {
    $this->renderer = $renderer;
    $this->mailer = $mailer;
  }

  public function index(Request $request, Response $response, array $args)
  {
    $args['pageTitle'] = "Joshua Flood";
    return $this->renderer->render($response, 'index.phtml', $args);
  }

  public function bio(Request $request, Response $response, array $args)
  {
    $args['pageTitle'] = "Bio | Joshua Flood";
    return $this->renderer->render($response, 'bio.phtml', $args);
  }

  public function showcase(Request $request, Response $response, array $args)
  {
    $args['pageTitle'] = "Showcase | Joshua Flood";
    return $this->renderer->render($response, 'showcase.phtml', $args);
  }

  public function contact(Request $request, Response $response, array $args)
  {
    $args['pageTitle'] = "Contact | Joshua Flood";
    return $this->renderer->render($response, 'contact.phtml', $args);
  }
}
