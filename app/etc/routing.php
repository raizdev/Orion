<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

/** @var \Slim\App $app */

$middleware = require_once __DIR__ . '/middleware.php';
$middleware($app);
