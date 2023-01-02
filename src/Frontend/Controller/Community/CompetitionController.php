<?php
namespace Orion\Frontend\Controller\Community;

use Orion\Core\Controller\BaseController;
use Orion\Core\Exception\DataObjectManagerException;
use Orion\Competition\Repository\CompetitionRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Orion\Core\Mapping\Annotation as CR;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use function response;

/**
 * Class CompetitionController
 *
 * @package Orion\Community\Controller
 * @CR\Router
 */
class CompetitionController extends BaseController
{
    /**
     * CompetitionController constructor.
     *
     * @param Twig                      $twig
     * @param CompetitionRepository     $competitionRepository
     */
    public function __construct(
        private Twig $twig,
        private CompetitionRepository $competitionRepository
    ) {}

    /**
     * @CR\Route(
     *     name="community-competition",
     *     methods={"GET"},
     *     pattern="/community/competitions"
     * )
     *
     * @param Request     $request
     * @param Response    $response
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function competitions(Request $request, Response $response): Response
    {
        $competitions = $this->competitionRepository->getPaginatedCompetitionList();

        return $this->twig->render($response, '/Frontend/Views/pages/community/competitions/competition.twig', [
            'competitions' => $competitions,
            'page' => 'community_competition'
        ]);
    }

    /**
     * @CR\Route(
     *     name="community-application",
     *     methods={"GET"},
     *     pattern="/community/competitions/{id}/apply"
     * )
     *
     * @param Request     $request
     * @param Response    $response
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function application(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        /** @var CompetitionRepository $competitions */
        $competition = $this->competitionRepository->get($id);

        return $this->twig->render($response, '/Frontend/Views/pages/community/competitions/application.twig', [
            'competition' => $competition,
            'page' => 'community_competition'
        ]);
    }
}
