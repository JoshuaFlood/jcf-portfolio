
<?php

use Slim\Psr7\Request;
use Slim\Psr7\Response;

$app->get('/', 'HomeController:index');

$app->get('/bio', 'HomeController:bio');

$app->get('/showcase', 'HomeController:showcase');

$app->get('/contact', 'HomeController:contact');

$app->post('/contact', 'HomeController:contactsubmit');
