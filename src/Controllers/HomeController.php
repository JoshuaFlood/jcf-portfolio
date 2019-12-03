<?php

namespace App\Controllers;

use Slim\Psr7\Request;
use Slim\Psr7\Response;

class HomeController
{
  protected $renderer;

  public function __construct($renderer)
  {
    $this->renderer = $renderer;
  }

  public function view(Request $request, Response $response, array $args)
  {
    return $this->renderer->render($response, 'index.phtml', $args);
  }

  public function about(Request $request, Response $response, array $args)
  {
    return $this->renderer->render($response, 'about.phtml', $args);
  }

  public function portfolio(Request $request, Response $response, array $args)
  {
    return $this->renderer->render($response, 'portfolio.phtml', $args);
  }

  public function contact(Request $request, Response $response, array $args)
  {
    return $this->renderer->render($response, 'contact.phtml', $args);
  }

  public function contactSubmit(Request $request, Response $response, array $args)
  {
    // $args['error'] = "";
    // $formData = $request->getParsedBody();
    // var_dump($formData);
    // $valid = $this->validateContactForm($formData);
    // if(
    //   $valid !== ""
    // ) {
    //   $args['error'] = $valid;
    //   return $this->renderer->render($response, 'contact.phtml', $args);
    // }
    return $this->renderer->render($response, 'contact.phtml', $args);
  }

  public function validateContactForm()
  {
    // $this->validateContactName($name)
    // && $this->validateContactEmail($email)
    // && $this->validateContactTel($tel)
    // && $this->validateContactMessage($message)
    // && $this->validateContactHuman($human)
    // && $this->validateContactRobot($robot)
  }





}
