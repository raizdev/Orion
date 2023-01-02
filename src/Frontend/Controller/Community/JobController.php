<?php
namespace Orion\Frontend\Controller\Community;

use Orion\Core\Controller\BaseController;
use Orion\Core\Exception\DataObjectManagerException;
use Orion\Job\Repository\JobRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Orion\Core\Mapping\Annotation as CR;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use function response;

/**
 * Class JobController
 *
 * @package Orion\Community\Controller
 * @CR\Router
 */
class JobController extends BaseController
{
    /**
     * StaffController constructor.
     *
     * @param Twig                  $twig
     * @param JobRepository  $josRepository
     */
    public function __construct(
        private Twig $twig,
        private JobRepository $jobRepository
    ) {}

    /**
     * @CR\Route(
     *     name="community-jobs",
     *     methods={"GET"},
     *     pattern="/community/jobs"
     * )
     *
     * @param Request     $request
     * @param Response    $response
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function listWithJobs(Request $request, Response $response): Response
    {
        /** @var JobRepository $jobs */
        $jobs = $this->jobRepository->findAll();
        
        return $this->twig->render($response, '/Frontend/Views/pages/community/jobs.twig', [
            'jobs' => $jobs,
            'page' => 'community_jobs'
        ]);
    }
}
