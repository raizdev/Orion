<?php
namespace Orion\Frontend\Controller\Community;

use Orion\Core\Controller\BaseController;
use Orion\Core\Exception\DataObjectManagerException;
use Orion\Job\Repository\JobRepository;
use Orion\Job\Repository\ApplicationRepository;
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
        private JobRepository $jobRepository,
        private ApplicationRepository $applicationRepository
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
        $user = user($request);

        /** @var JobRepository $jobs */
        $jobs = $this->jobRepository->findAll();

        /** @var ApplicationRepository $jobs */
        $applications = $this->applicationRepository->getApplicationByUser($user->id);

        return $this->twig->render($response, '/Frontend/Views/pages/community/jobs.twig', [
            'jobs' => $jobs,
            'applications' => $applications,
            'page' => 'community_jobs'
        ]);
    }
}
