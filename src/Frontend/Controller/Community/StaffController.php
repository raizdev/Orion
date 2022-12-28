<?php
namespace Orion\Frontend\Controller\Community;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Orion\Permission\Repository\PermissionRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Cosmic\Core\Mapping\Annotation as CR;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use function response;

/**
 * Class StaffController
 *
 * @package Ares\Community\Controller
 * @CR\Router
 */
class StaffController extends BaseController
{
    /**
     * StaffController constructor.
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
     *     pattern="/community/staff"
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
            'users' => $users,
            'page' => 'community_staff'
        ]);
    }
}
