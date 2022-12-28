<?php
use Ares\Framework\Exception\AuthenticationException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Framework\Model\CustomResponse;
use Ares\Framework\Model\Query\Collection;
use Ares\Framework\Proxy\App;
use Ares\Framework\Repository\BaseRepository;
use Ares\Framework\Service\LocaleService;
use Ares\User\Entity\User;
use Ares\User\Interfaces\Response\UserResponseCodeInterface;
use Ares\User\Repository\UserRepository;
use League\Container\Container;
use Odan\Session\SessionInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

if (!function_exists('dd')) {
    /**
     * Debug
     *
     * @print var
     */
    function dd($var) {
        echo '<pre>';
        print_r($var);
        exit;
    }
}

if (!function_exists('__')) {
    /**
     * Takes message and placeholder to translate them to global locale.
     *
     * @param string $key
     * @param array $placeholder
     * @return string
     */
    function __(string $key, array $placeholder = []): string {
        $container = App::getContainer();
        /** @var LocaleService $localeService */
        $localeService = $container->get(LocaleService::class);
        return $localeService->translate($key, $placeholder);
    }
}

if (!function_exists('response')) {
    /**
     * Returns instance of custom response.
     *
     * @return CustomResponseInterface
     */
    function response(): CustomResponseInterface {
        $container = App::getContainer();
        return $container->get(CustomResponse::class);
    }
}

if (!function_exists('app_dir')) {
    /**
     * Returns directory path of app.
     *
     * @return string
     */
    function app_dir(): string {
        return __DIR__ . '/..';
    }
}

if (!function_exists('base_dir')) {
    /**
     * Returns directory path of root.
     *
     * @return string
     */
    function base_dir(): string {
        return __DIR__ . '/../../';
    }
}

if (!function_exists('cache_dir')) {
    /**
     * Returns directory path of cache.
     *
     * @return string
     */
    function cache_dir(): string {
        return __DIR__ . '/../../tmp/Cache';
    }
}

if (!function_exists('tmp_dir')) {
    /**
     * Returns directory path of tmp directory.
     *
     * @return string
     */
    function tmp_dir(): string {
        return __DIR__ . '/../../tmp';
    }
}

if (!function_exists('src_dir')) {
    /**
     * Returns directory path of src directory.
     *
     * @return string
     */
    function src_dir(): string {
        return __DIR__ . '/../../src';
    }
}

if (!function_exists('route_cache_dir')) {
    /**
     * Returns directory path of routing cache.
     *
     * @return string
     */
    function route_cache_dir(): string {
        return __DIR__ . '/../../tmp/Cache/routing';
    }
}

if (!function_exists('container')) {
    /**
     * Returns di container instance.
     *
     * @return Container
     */
    function container(): Container
    {
        return App::getContainer();
    }
}

if (!function_exists('repository')) {
    /**
     * Returns repository by given namespace.
     *
     * @param string $repository
     * @return BaseRepository
     */
    function repository(string $repository): ?BaseRepository {
        $container = container();
        $repository = $container->get($repository);

        if (!$repository instanceof BaseRepository) {
            throw new DataObjectManagerException(
                __('Tried to instantiating not existing repository "%s"', [$repository]),
                500
            );
        }

        return $repository;
    }
}

if (!function_exists('accumulate')) {
    /**
     * Returns data as new collection.
     *
     * @param mixed $items
     * @return Collection
     */
    function accumulate($items = null): Collection {
        return new Collection($items);
    }
}

if (!function_exists('user')) {
    /**
     * Returns current authenticated user.
     * Required classes: \Ares\User\Repository\UserRepository, \Ares\User\Entity\User
     *
     * @param Request $request
     * @param bool $isCached
     *
     * @return User
     * @throws AuthenticationException
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    function user(Request $request, bool $isCached = false): User {

        $container = App::getContainer();
        /** @var LocaleService $localeService */
        $sessionService = $container->get(SessionInterface::class);

        /** @var array; $user */
        $authUser = $request->getAttribute('cosmic_uid');

        if (!$authUser || !$sessionService->has('token')) {
            throw new AuthenticationException(
                __('Not logged in.'),
                UserResponseCodeInterface::RESPONSE_NOT_ALLOWED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNAUTHORIZED
            );
        }

        /** @var UserRepository $userRepository */
        $userRepository = container()->get(UserRepository::class);

        /** @var User $user */
        $user = $userRepository->get((int) $authUser, User::COLUMN_ID, $isCached);
        $user->getCurrencies();

        if (!$user) {
            throw new AuthenticationException(
                __('User doesnt exists.'),
                UserResponseCodeInterface::RESPONSE_NOT_ALLOWED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNAUTHORIZED
            );
        }

        return $user;
    }
}
