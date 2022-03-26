<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

use Slim\App;
use Slim\Views\Twig;
use Odan\Session\Middleware\SessionMiddleware;

/**
 * Registers our Global Middleware
 *
 * @param App $app
 */
return function (App $app) {
    $container = $app->getContainer();
    $logger = $container->get(\Psr\Log\LoggerInterface::class);
    $twig = $container->get(Twig::class);

    $app->addRoutingMiddleware();

    $app->add(\Slim\Views\TwigMiddleware::create($app, $twig));
    $app->add(\Odan\Session\Middleware\SessionMiddleware::class);

    $app->addErrorMiddleware(true, true, true, $logger);
};
