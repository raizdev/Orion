<?php
use Psr\Http\Message\ServerRequestInterface;
return [
    // Adds our LocaleProvider to add locales
    $container->addServiceProvider(
        new \Orion\Core\Provider\LocaleServiceProvider()
    ),
    // Adds our ConfigProvider
    $container->addServiceProvider(
        new \Orion\Core\Provider\ConfigServiceProvider()
    ),
    // Adds our LoggingProvider
    $container->addServiceProvider(
        new \Orion\Core\Provider\LoggingServiceProvider()
    ),
    // Adds our AppProvider and creates App
    $container->addServiceProvider(
        new \Orion\Core\Provider\AppServiceProvider()
    ),
    $container->addServiceProvider(
        new \Orion\Core\Provider\RouteCollectorServiceProvider()
    ),
    // Adds our RouteProvider
    $container->addServiceProvider(
        new \Orion\Core\Provider\RouteServiceProvider()
    ),
    // Adds our ValidationProvider
    $container->addServiceProvider(
        new \Orion\Core\Provider\ValidationServiceProvider()
    ),
    // Adds our CacheServiceProvider
    $container->addServiceProvider(
        new \Orion\Core\Provider\CacheServiceProvider()
    ),
    // Adds our SlugServiceProvider
    $container->addServiceProvider(
        new \Orion\Core\Provider\SlugServiceProvider()
    ),
    // Adds our ThrottleServiceProvider
    $container->addServiceProvider(
        new \Orion\Core\Provider\ThrottleServiceProvider()
    ),
    // Adds our TwigServiceProvider
    $container->addServiceProvider(
        new \Orion\Core\Provider\TwigServiceProvider()
    ),
    // Adds our SessionServiceProvider
    $container->addServiceProvider(
        new \Orion\Core\Provider\SessionServiceProvider()
    )
];