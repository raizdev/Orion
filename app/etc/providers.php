<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

/**
 * Registers our ServiceProviders
 */

use Psr\Http\Message\ServerRequestInterface;
return [
    // Adds our LocaleProvider to add locales
    $container->addServiceProvider(
        new \Ares\Framework\Provider\LocaleServiceProvider()
    ),
    // Adds our ConfigProvider
    $container->addServiceProvider(
        new \Ares\Framework\Provider\ConfigServiceProvider()
    ),
    // Adds our LoggingProvider
    $container->addServiceProvider(
        new \Ares\Framework\Provider\LoggingServiceProvider()
    ),
    // Adds our AppProvider and creates App
    $container->addServiceProvider(
        new \Cosmic\Core\Provider\AppServiceProvider()
    ),
    $container->addServiceProvider(
        new \Cosmic\Core\Provider\RouteCollectorServiceProvider()
    ),
    // Adds our RouteProvider
    $container->addServiceProvider(
        new \Ares\Framework\Provider\RouteServiceProvider()
    ),
    // Adds our ValidationProvider
    $container->addServiceProvider(
        new \Ares\Framework\Provider\ValidationServiceProvider()
    ),
    // Adds our CacheServiceProvider
    $container->addServiceProvider(
        new \Ares\Framework\Provider\CacheServiceProvider()
    ),
    // Adds our SlugServiceProvider
    $container->addServiceProvider(
        new \Ares\Framework\Provider\SlugServiceProvider()
    ),
    // Adds our ThrottleServiceProvider
    $container->addServiceProvider(
        new \Ares\Framework\Provider\ThrottleServiceProvider()
    ),
    // Adds our TwigServiceProvider
    $container->addServiceProvider(
        new \Cosmic\Core\Provider\TwigServiceProvider()
    ),
    // Adds our SessionServiceProvider
    $container->addServiceProvider(
        new \Cosmic\Core\Provider\SessionServiceProvider()
    )
];