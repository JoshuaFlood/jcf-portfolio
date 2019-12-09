<?php

use PHPMailer\PHPMailer\PHPMailer;

$container = $app->getContainer();

$container->set('logger', function ($c) {
  $logger = new Monolog\Logger('jcf-portfolio');
  $logger->pushProcessor(new Monolog\Processor\UidProcessor());
  if (!empty(__DIR__ . '/../var/log/jcf-portfolio.error.log')) {
    $logger->pushHandler(new Monolog\Handler\StreamHandler(
      __DIR__ . '/../var/log/slim-php-template.error.log',
      Monolog\Logger::DEBUG
    ));
  } else {
    $logger->pushHandler(new Monolog\Handler\ErrorLogHandler(
      0,
      Monolog\Logger::DEBUG, true, true
    ));
  }
  return $logger;
});

// view renderer
$container->set('renderer', function ($c) {
  return new Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});

// view renderer
$container->set('mailer', function ($c) {
  $mail = new PHPMailer;
  $mail->From = "no-reply@joshuaflood.com";
  $mail->FromName = "Joshua Flood Portfolio";
  $mail->addAddress("info@joshuaflood.com", "Joshua Flood");
  $mail->isHTML(true);
  $mail->Subject = "Someone used your contact form!";
  return $mail;
});

$container->set('mysql', function ($c) {
  $host = getenv("MYSQL_HOST");
  $db = getenv("MYSQL_DB");
  $charset = getenv("MYSQL_CHARSET");
  $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
  $options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false
  ];
  $user = getenv("MYSQL_USER");
  $pass = getenv("MYSQL_PASS");
  try {
    $pdo = new PDO($dsn, $user, $pass, $options);
  } catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
  }
});

$container->set('HomeController', function($c) {
  $renderer = $c->get('renderer');
  $mailer = $c->get('mailer');
  return new App\Controllers\HomeController($renderer,$mailer);
});
