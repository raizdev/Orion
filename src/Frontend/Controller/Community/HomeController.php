<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Frontend\Controller\Community;

use Ares\Room\Repository\RoomRepository;
use Cosmic\Core\Mapping\Annotation as CR;
use Ares\Article\Repository\ArticleRepository;
use Ares\Tag\Repository\TagRepository;
use Ares\Tag\Entity\Contract\TagInterface;
use Ares\Badge\Service\BadgeAlbumService;
use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Tag\Service\CreateTagService;
use Ares\Tag\Service\DeleteTagService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Ares\Framework\Service\ValidationService;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 *  @package Ares\Frontend\Controller
 * @CR\Router
 * @CR\Group(
 *     prefix="community",
 *     pattern="community"
 * )
 */

class HomeController extends BaseController
{
    /**
     * IndexController constructor.
     * @param Twig $twig
     * @param RoomRepository $roomRepository
     */
    public function __construct(
        private Twig $twig,
        private RoomRepository $roomRepository,
        private ArticleRepository $articleRepository,
        private BadgeAlbumService $badgeService,
        private TagRepository $tagRepository,
        private ValidationService $validationService,
        private CreateTagService $createTagService,
        private DeleteTagService $deleteTagService
    ) {}

    /**
     *
     * @CR\Route(
     *     name="delete-tag",
     *     methods={"POST"},
     *     pattern="/delete-tag"
     * )
     *
     * Delete new tag.
     *
     * @param Request  $request
     * @param Response $response
     * 
     * @throws AuthenticationException
     * @throws DataObjectManagerException
     * @throws ValidationException
     * @throws VoteException
     * @throws NoSuchEntityException
     */

    public function deleteTag(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            TagInterface::COLUMN_ID => 'required'
        ]);

        /** @var User $user */
        $user = user($request);

        $customResponse = $this->deleteTagService
        ->execute(
            $user->getId(),
            $parsedData
        );

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     *
     * @CR\Route(
     *     name="create-tag",
     *     methods={"POST"},
     *     pattern="/create-tag"
     * )
     *
     * Create new like.
     *
     * @param Request  $request
     * @param Response $response
     * 
     * @throws AuthenticationException
     * @throws DataObjectManagerException
     * @throws ValidationException
     * @throws VoteException
     * @throws NoSuchEntityException
     */

    public function createTag(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            TagInterface::COLUMN_TAG => 'required'
        ]);

        /** @var User $user */
        $user = user($request);

        $customResponse = $this->createTagService
        ->execute(
            $user->getId(),
            $parsedData
        );

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     *
     * @CR\Route(
     *     name="home",
     *     methods={"GET"},
     *     pattern="/"
     * )
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function home(Request $request, Response $response): Response
    {
        /** @var $questions */
        $questions = explode(';', __('website.hotel.tags_questions'));

        /** @var $user */
        $user = user($request);

        /** @var roomRepository $rooms */
        $rooms = $this->roomRepository->getMostVisitedRoom();

        /** @var roomRepository $roomsByScore */
        $roomsByScore = $this->roomRepository->getByScore();

        /** @var articleRepository $articles */
        $articles = $this->articleRepository->getPaginatedArticleList(1, 2);

        /** @var articleRepository $tags */
        $tags = $this->tagRepository->getPaginatedUserTagList($user->id);

        /** @var badgeService $badges */
        $badges = $this->badgeService->execute();

        return $this->twig->render($response, '/Frontend/Views/pages/community/home.twig', [
            'page'  => 'community',
            'rooms' => $rooms,
            'articles' => $articles,
            'badges' => $badges,
            'tags' => $tags,
            'tagQuestions' => $questions,
            'roomsByScore' => $roomsByScore
        ]);
    }
}
