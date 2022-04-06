<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Forum\Controller;

use Ares\Forum\Entity\Contract\ThreadInterface;
use Ares\Forum\Entity\Thread;
use Ares\Forum\Exception\ThreadException;
use Ares\Forum\Repository\ThreadRepository;
use Ares\Forum\Service\Thread\CreateThreadService;
use Ares\Forum\Service\Thread\DeleteThreadService;
use Ares\Forum\Service\Thread\EditThreadService;
use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\AuthenticationException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\User\Entity\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class ThreadController
 *
 * @package Ares\Forum\Controller
 */
class ThreadController extends BaseController
{
    /**
     * ThreadController constructor.
     *
     * @param ThreadRepository    $threadRepository
     * @param CreateThreadService $createThreadService
     * @param EditThreadService   $editThreadService
     * @param ValidationService   $validationService
     * @param DeleteThreadService $deleteThreadService
     */
    public function __construct(
        private ThreadRepository $threadRepository,
        private CreateThreadService $createThreadService,
        private EditThreadService $editThreadService,
        private ValidationService $validationService,
        private DeleteThreadService $deleteThreadService
    ) {}

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws AuthenticationException
     * @throws DataObjectManagerException
     * @throws ThreadException
     * @throws ValidationException
     * @throws NoSuchEntityException
     */
    public function create(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            ThreadInterface::COLUMN_TITLE => 'nullable|regex:/^[a-zA-Z0-9]+$/',
            ThreadInterface::COLUMN_DESCRIPTION => 'nullable',
            ThreadInterface::COLUMN_CONTENT => 'nullable',
            ThreadInterface::COLUMN_TOPIC_ID => 'required|numeric'
        ]);

        /** @var User $user */
        $userId = user($request)->getId();

        $customResponse = $this->createThreadService->execute($userId, $parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     */
    public function thread(Request $request, Response $response, array $args): Response
    {
        /** @var string $slug */
        $slug = $args['slug'];

        /** @var int $topicId */
        $topicId = $args['topic_id'];

        /** @var Thread $thread */
        $thread = $this->threadRepository
            ->getSingleThread(
                $topicId,
                $slug
            );

        return $this->respond(
            $response,
            response()
                ->setData($thread)
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
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

        /** @var int $topicId */
        $topicId = $args['topic_id'];

        $threads = $this->threadRepository
            ->getPaginatedThreadList(
                $topicId,
                $page,
                $resultPerPage
            );

        return $this->respond(
            $response,
            response()
                ->setData($threads)
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws ThreadException
     * @throws DataObjectManagerException
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $customResponse = $this->deleteThreadService->execute($id);

        return $this->respond(
            $response,
            $customResponse
        );
    }
}
