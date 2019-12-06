<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$container = $app->getContainer();

$container->set('logger', function ($c) {
  $logger = new Monolog\Logger('jcf-portfolio');
  $logger->pushProcessor(new Monolog\Processor\UidProcessor());
  if (!empty(__DIR__ . '/../var/log/jcf-portfolio.error.log')) {
    $logger->pushHandler(new Monolog\Handler\StreamHandler(__DIR__ . '/../var/log/slim-php-template.error.log', Monolog\Logger::DEBUG));
  } else {
    $logger->pushHandler(new Monolog\Handler\ErrorLogHandler(0, Monolog\Logger::DEBUG, true, true));
  }
  return $logger;
});

// view renderer
$container->set('renderer', function ($c) {
  return new Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});

// view renderer
$container->set('phpmailer', function ($c) {
  $mail = new PHPMailer(true);
  try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.joshuaflood.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'no-reply@joshuaflood.com';                     // SMTP username
    $mail->Password   = 'thisshouldbestoredindotenv';                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->Port       = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('no-reply@joshuaflood.com', 'Mailer');
    $mail->addAddress('info@joshuaflood.com', 'Joshua Flood');     // Add a recipient
    $mail->addReplyTo($params['email'], $params['name']);

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Portfolio: ' . $params['name'] . ' used the contact form!';
    $mail->Body    = $params['message'];
    $mail->AltBody = strip_tags($params['message']);

    $mail->send();
    echo 'Message has been sent';
    $mail->clearAddresses();
  } catch (Exception $e) {
    echo 'Mailer Error (' . htmlspecialchars($row['email']) . ') ' . $mail->ErrorInfo . '<br>';
    $mail->smtp->reset();
  }
  // Redirect to contact page with error message added to params.
  return new Slim\Views\PhpRenderer(__DIR__ . '/../templates');
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
  return new App\Controllers\HomeController($renderer);
});
