## Installation
#### Preferable with nginx on debian 10

##### 1. Clone project.
```console
$ git clone https://github.com/raizdev/orion.git ./
```
##### 2. Copy dotenv config.
```console
$ cp .env_example .env 
```
##### 3. Install composer dependencies.
```console
$ composer install 
```
##### 4. Give rights to the folder.
```console
$ chmod -R 775 {dir name} 
```

## Configuration
##### Configure your .env:

```text
# Database Credentials
DB_HOST=db
DB_PORT=db
DB_NAME="orion"
DB_USER="orion"
DB_PASSWORD="your_password"

# Api Mode | development / production
API_DEBUG="production"

# Name of the Application and 
WEB_NAME="Orion"
WEB_FRONTEND_LINK="*"

# 1 = Enabled, 0 = Disabled
CACHE_ENABLED=1
# "Files" for Filecache, "Predis" for Redis
CACHE_TYPE="Files"
# Defines the cache alive time in seconds
CACHE_TTL=950

# Redis if Cache is enabled and type is Predis
CACHE_REDIS_HOST=127.0.0.1
# Redis Port, standard Port is 6379
CACHE_REDIS_PORT=6379

# Only works with Redis // Enables Throttling // Enables that people only can call the endpoint certain times in a short period
THROTTLE_ENABLED=0

TOKEN_ISSUER="Orion-Issuer"
TOKEN_DURATION=86400

# The secret must be at least 12 characters in length; contain numbers; upper and lowercase letters; and one of the following special characters *&!@%^#$
TOKEN_SECRET="Secret123!456$"

```
##### 6. Setup your database with Morningstar or whatever you are going to use.
```console
$ ---------
```

##### 7. Migrate Ares tables to your database.
```console
$ vendor/bin/phinx migrate
```

##### 8. Finished.

## Expand project

### Create custom Module:

##### 1. Create controller class which extends BaseController
In the controller class you can define functions that are called by calling a route,
which can be defined in the app/routes/routes.php.
Auth protected routes can be used with setting middleware 'AuthMiddleware',
between route path and route action call.

```php
<?php

namespace Orion\CustomModule\Controller;

use Orion\Framework\Controller\BaseController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class Controller
 *
 * @package Orion\CustomModule\Controller
 */
class Controller extends BaseController
{
    /**
     * Reveals a custom response to the user
     *
     * @param Request  $request  The current incoming Request
     * @param Response $response The current Response
     *
     * @return Response Returns a Response with the given Data
     */
    public function customResponse(Request $request, Response $response): Response
    {
        /** @var string $customResponse */
        $customResponse = 'your custom response';

        return $this->respond(
            $response,
            response()
                ->setData($customResponse)
        );
    }
}
```

### Create custom Service Provider:

##### 1. Create new Service Provider with extending AbstractServiceProvider
```php
<?php

namespace Orion\CustomModule\Provider;

use Orion\CustomModule\Model\Custom;
use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * Class CustomServiceProvider
 *
 * @package Orion\CustomModule\Provider
 */
class CustomServiceProvider extends AbstractServiceProvider
{
    /**
     * @var string[]
     */
    protected $provides = [
        Custom::class
    ];

    /**
     * Registers new service.
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->add(Custom::class, function () {
            return new Custom();
        });
    }
}
```

##### 2. Register the new created Service Provider in app/etc/providers.php
```php
    // Adds our CustomProvider to add Customs
    $container->addServiceProvider(
        new \Orion\CustomModule\Provider\CustomServiceProvider()
    );
```

## Credits
If you got questions or feedback feel free to contact us.

- Discord: Dome#9999
- Mail: dominik-forschner@web.de
----------------------------------
- Discord: s1njar#0066
- Mail: s1njar.mail@gmail.com

## Links

- [Slim Framework](https://www.slimframework.com/)

## License

The MIT License (MIT).
