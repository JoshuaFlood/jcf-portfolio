
<?php

use Slim\Psr7\Request;
use Slim\Psr7\Response;

$app->get('/', 'HomeController:view');

$app->get('/about', 'HomeController:about');

$app->get('/portfolio', 'HomeController:portfolio');

$app->get('/contact', 'HomeController:contact');

$app->post('/contact', 'HomeController:contactsubmit');
