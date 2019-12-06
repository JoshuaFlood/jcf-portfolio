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
    // Get form data.
    $data = $request->getParsedBody();

    // Check for robots.
    if($data['roboto'] !== "") {
      // Return robots to contact page without performing any action.
      return $this->renderer->render($response, 'contact.phtml', $args);
    }

    // Validate contact form.
    $args['error'] = validateContactForm($data);

    // Ensure no errors were found.
    if(count($args['error']) === 0) {

      // Set mailer params
      $this->mailer->setFrom(trim($data['email']), trim($data['name']));
      $this->mailer->addAddress('info@joshuaflood.com');
      $this->mailer->addReplyTo(trim($data['email']), trim($data['name']));
      $this->mailer->isHTML(true);

      // Create message
      $this->mailer->Subject = 'Portfolio contact from ' . trim($data['name']) . '.';
      $this->mailer->Body    = "
        <p>NEW PORTFOLIO CONTACT:</p>
        <h1>" . $data['name'] . " &lt; " . trim($data['email']) . " &gt;</h1>
        <hr />
        <h3>Message:</h3>
        <p>" . $data['message'] . "</p>
        <hr />
        <small>" . $data['name'] . "</small>
        <small>" . $data['email'] . "</small>
        <small>" . $data['tel'] . "</small>";

      // Try sending mail
      if(!$this->mailer->send()) {
        // Return generic error message to user if there is a problem sending
        // mail. This error should be logged using monolog.
        $args['error']['general'] = "We're having trouble with our mail servers
          at the moment.  Please try again later, or contact me using the social
          media links at the top of the page.";
        return $this->renderer->render($response, 'contact.phtml', $args);
      }

      // Redirect user to success page.
      return $this->renderer->render($response, 'contact-success.phtml', $args);
    }
    return $this->renderer->render($response, 'contact.phtml', $args);
  }

  public function validateContactForm($data)
  {
    $error = [];
    // Validate name.
    if($data['name'] === "") {
      $error['name'] = "Please provide your full name!";
    } else if(!validateName($data['name'])){
      $error['name'] = "Please enter a valid name!";
    }
    // Validate e-mail.
    if($data['email'] === "") {
      $error['email'] = "Please provide an e-mail address.";
    } else if(!validateEmail($data['email'])){
      $error['email'] = "The e-mail address you entered is invalid!";
    }
    // Validate message.
    if(count($data['message']) < 50) {
      $error['message'] = "Please provide a message which contains at least 50 characters!";
    }
    // Validate Voight-Kampff test.
    if($data['human'] === "") {
      $error['human'] = "Please provide an answer to this highly sophisticated Voight-Kampff test!";
    } else if($data['human'] !== "16") {
      // Add captcha or something.
      $error['human'] = "The answer you provided is incorrect. Please try again!";
    }
    return $error;
  }

  public function validateName($name)
  {
    return (!preg_match("/^([a-zA-Z' ]+)$/",$name)) ? FALSE : TRUE;
  }

  public function validateEmail($email)
  {
    return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) ? FALSE : TRUE;
  }
}
