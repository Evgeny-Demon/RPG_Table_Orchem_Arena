<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/config/constants.php';

use app\controller\Router;

$router = new Router();
$router->route();