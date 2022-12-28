<?php
/** @var \Slim\App $app */

$middleware = require_once __DIR__ . '/middleware.php';
$middleware($app);
