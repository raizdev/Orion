<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\Role\Entity\Contract\RoleHierarchyInterface;
use Ares\Role\Entity\Contract\RoleInterface;
use Ares\Role\Entity\Contract\RoleRankInterface;
use Ares\Role\Exception\RoleException;
use Ares\Role\Repository\RoleRepository;
use Ares\Role\Service\AssignRankToRoleService;
use Ares\Role\Service\CreateChildRoleService;
use Ares\Role\Service\CreateRoleService;
use Ares\Role\Service\DeleteRoleService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class RoleController
 *
 * @package Ares\Role\Controller
 */
class RoleController extends BaseController
{
    /**
     * RoleController constructor.
     *
     * @param CreateRoleService       $createRoleService
     * @param CreateChildRoleService  $createChildRoleService
     * @param AssignRankToRoleService $assignRankToRoleService
     * @param ValidationService       $validationService
     * @param DeleteRoleService       $deleteRoleService
     * @param RoleRepository          $roleRepository
     */
    public function __construct(
        private CreateRoleService $createRoleService,
        private CreateChildRoleService $createChildRoleService,
        private AssignRankToRoleService $assignRankToRoleService,
        private ValidationService $validationService,
        private DeleteRoleService $deleteRoleService,
        private RoleRepository $roleRepository
    ) {}

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function list(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $roles = $this->roleRepository
            ->getPaginatedRoles(
                $page,
                $resultPerPage
            );

        return $this->respond(
            $response,
            response()
                ->setData($roles)
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws RoleException
     * @throws ValidationException|NoSuchEntityException
     */
    public function createRole(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            RoleInterface::COLUMN_NAME => 'required',
            RoleInterface::COLUMN_DESCRIPTION => 'required'
        ]);

        $customResponse = $this->createRoleService->execute($parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws RoleException
     * @throws ValidationException
     * @throws NoSuchEntityException
     */
    public function createChildRole(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            RoleHierarchyInterface::COLUMN_PARENT_ROLE_ID => 'numeric|required',
            RoleHierarchyInterface::COLUMN_CHILD_ROLE_ID => 'numeric|required'
        ]);

        $customResponse = $this->createChildRoleService->execute($parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     * @throws RoleException
     * @throws ValidationException
     */
    public function assignRole(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            RoleRankInterface::COLUMN_RANK_ID => 'numeric|required',
            RoleRankInterface::COLUMN_ROLE_ID => 'numeric|required'
        ]);

        $customResponse = $this->assignRankToRoleService->execute($parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws RoleException
     * @throws DataObjectManagerException
     */
    public function deleteRole(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $customResponse = $this->deleteRoleService->execute($id);

        return $this->respond(
            $response,
            $customResponse
        );
    }
}
