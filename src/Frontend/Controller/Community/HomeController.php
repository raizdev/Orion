<?php
namespace Orion\Frontend\Controller\Community;

use Orion\Room\Repository\RoomRepository;
use Orion\Core\Mapping\Annotation as CR;
use Orion\Article\Repository\ArticleRepository;
use Orion\Tag\Repository\TagRepository;
use Orion\Tag\Entity\Contract\TagInterface;
use Orion\Badge\Service\BadgeAlbumService;
use Orion\Core\Controller\BaseController;
use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Exception\NoSuchEntityException;
use Orion\Tag\Service\CreateTagService;
use Orion\Tag\Service\DeleteTagService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Orion\Core\Service\ValidationService;
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
