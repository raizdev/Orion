<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Frontend\Controller\Permission;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Permission\Repository\PermissionRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Cosmic\Core\Mapping\Annotation as CR;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use function response;

/**
 * Class PermissionController
 *
 * @package Ares\Permission\Controller
 * @CR\Router
 */
class PermissionController extends BaseController
{
    /**
     * PermissionController constructor.
     *
     * @param Twig                  $twig
     * @param PermissionRepository  $permissionRepository
     */
    public function __construct(
        private Twig $twig,
        private PermissionRepository $permissionRepository
    ) {}

    /**
     * @CR\Route(
     *     name="community-staffs",
     *     methods={"GET"},
     *     pattern="/community/staff/list"
     * )
     *
     * @param Request     $request
     * @param Response    $response
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function listUserWithRank(Request $request, Response $response): Response
    {
        $users = $this->permissionRepository->getListOfUserWithRanks();

        return $this->twig->render($response, '/Frontend/Views/pages/community/staff.twig', [
            'staffs' => $users,
            'page' => 'Staff list'
        ]);
    }
}
