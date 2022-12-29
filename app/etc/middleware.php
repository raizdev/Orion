<?php
use Orion\Core\Middleware\LocaleMiddleware;
use Orion\Core\Middleware\ClaimMiddleware;
use Odan\Session\Middleware\SessionMiddleware;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

/**
 * Registers our Global Middleware
 *
 * @param App $app
 *
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
return function (App $app) {
    $container = $app->getContainer();
    $logger = $container->get(LoggerInterface::class);
    $twig = $container->get(Twig::class);

    $app->add(TwigMiddleware::create($app, $twig));
    $app->add(SessionMiddleware::class);
    $app->add(ClaimMiddleware::class);
    $app->add(LocaleMiddleware::class);

    $errorMiddleware = $app->addErrorMiddleware(true, true, true, $logger);
    $errorMiddleware->setDefaultErrorHandler(\Orion\Core\Handler\ErrorHandler::class);
};
