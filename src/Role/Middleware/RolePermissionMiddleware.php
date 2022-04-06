<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Middleware;

use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Role\Exception\RoleException;
use Ares\Role\Interfaces\Response\RoleResponseCodeInterface;
use Ares\Role\Service\CheckAccessService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;

/**
 * Class RolePermissionMiddleware
 *
 * @package Ares\Role\Middleware
 */
class RolePermissionMiddleware implements MiddlewareInterface
{
    /**
     * RolePermissionMiddleware constructor.
     *
     * @param CheckAccessService $checkAccessService
     */
    public function __construct(
        private CheckAccessService $checkAccessService
    ) {}

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @throws RoleException|NoSuchEntityException
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {

        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();

        $permissionName = $route->getName();

        if ($permissionName === null) {
            return $handler->handle($request);
        }

        $userRank = user($request)->getRank();
        
        $isPermitted = $this->checkAccessService->execute($userRank, $permissionName);

        if (!$isPermitted) {
            throw new RoleException(
                __('You dont have the special rights to execute that action'),
                RoleResponseCodeInterface::RESPONSE_ROLE_NO_RIGHTS_TO_EXECUTE_ACTION,
                HttpResponseCodeInterface::HTTP_RESPONSE_FORBIDDEN
            );
        }

        return $handler->handle($request);
    }
}
