<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

use Slim\App;
use Slim\Views\Twig;

/**
 * Registers our Global Middleware
 *
 * @param App $app
 */
return function (App $app) {
    $container = $app->getContainer();
    $logger = $container->get(\Psr\Log\LoggerInterface::class);
    $twig = $container->get(Twig::class);

    $app->add(new Tuupola\Middleware\CorsMiddleware([
        "origin" => [$_ENV['WEB_FRONTEND_LINK']],
        "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
        "headers.allow" => ["Content-Type", "Authorization", "If-Match", "If-Unmodified-Since", "Origin"],
        "headers.expose" => ["Content-Type", "Etag", "Origin", "Last-Modified"],
        "credentials" => true,
        "cache" => $_ENV['TOKEN_DURATION']
    ]));

    $app->add(
        new Slim\Middleware\Session([
            'name' => $_ENV['SESSION_NAME'],
            'autorefresh' => true,
            'lifetime' => $_ENV['SESSION_LIFETIME'],
        ])
    );

    // Can be enabled when not using Twig
    // $app->add(\Ares\Framework\Middleware\BodyParserMiddleware::class);
    $app->add(\Ares\Framework\Middleware\ClaimMiddleware::class);
    $app->add(\Slim\Views\TwigMiddleware::create($app, $twig));
    $app->addRoutingMiddleware();

    $errorMiddleware = $app->addErrorMiddleware(true, true, true, $logger);
    $errorMiddleware->setDefaultErrorHandler(\Ares\Framework\Handler\ErrorHandler::class);
};
