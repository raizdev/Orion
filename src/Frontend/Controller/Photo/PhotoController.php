<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Frontend\Controller\Photo;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\Photo\Entity\Photo;
use Ares\Photo\Repository\PhotoRepository;
use Ares\User\Entity\Contract\UserInterface;
use Cosmic\Core\Mapping\Annotation as CR;
use Ares\User\Entity\User;
use Ares\User\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use function __;
use function response;

/**
 * Class PhotoController
 *
 * @package Ares\Photo\Controller
 * @CR\Router
 */
class PhotoController extends BaseController
{
    /**
     * PhotoController constructor.
     *
     * @param   Twig                    $twig
     * @param   PhotoRepository         $photoRepository
     * @param   UserRepository          $userRepository
     * @param   ValidationService       $validationService
     */
    public function __construct(
        private Twig $twig,
        private PhotoRepository $photoRepository,
        private UserRepository $userRepository,
        private ValidationService $validationService
    ) {}

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws NoSuchEntityException
     * @throws ValidationException
     */
    public function search(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            UserInterface::COLUMN_USERNAME => 'required'
        ]);

        /** @var string $username */
        $username = $parsedData['username'];

        /** @var User $user */
        $user = $this->userRepository->get($username, 'username');

        /** @var Photo $photo */
        $photo = $this->photoRepository->get($user->getId(), 'user_id');

        return $this->respond(
            $response,
            response()
                ->setData($photo)
        );
    }

    /**
     *  @CR\Route(
     *     name="community-photos",
     *     methods={"GET"},
     *     pattern="/community/photos"
     * )
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'] ?? 1;

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'] ?? 12;

        $photos = $this->photoRepository
            ->getPaginatedPhotoList(
                $page,
                $resultPerPage
            );

        return $this->twig->render($response, '/Frontend/Views/pages/community/photo.twig', [
            'photos' => $photos,
            'page' => 'Photos'
        ]);
    }
}
