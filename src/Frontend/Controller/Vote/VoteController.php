<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Frontend\Controller\Vote;

use Cosmic\Core\Mapping\Annotation as CR;
use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\AuthenticationException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Framework\Service\ValidationService;
use Ares\User\Entity\User;
use Ares\Vote\Entity\Contract\VoteInterface;
use Ares\Vote\Exception\VoteException;
use Ares\Vote\Interfaces\Response\VoteResponseCodeInterface;
use Ares\Vote\Repository\VoteRepository;
use Ares\Vote\Service\CreateVoteService;
use Ares\Vote\Service\DeleteVoteService;
use Ares\Vote\Service\Votes\DecrementVoteService;
use Ares\Vote\Service\Votes\IncrementVoteService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteParser;
use function __;
use function response;
use function user;

/**
 * Class VoteController
 *
 * @package Ares\Vote\Controller
 *
 * @CR\Router
 *  @CR\Group(
 *     prefix="vote",
 *     pattern="vote"
 * )
 */
class VoteController extends BaseController
{
    /**
     * VoteController constructor.
     *
     * @param   VoteRepository          $voteRepository
     * @param   ValidationService       $validationService
     * @param   CreateVoteService       $createVoteService
     * @param   DeleteVoteService       $deleteVoteService
     * @param   IncrementVoteService    $incrementVoteService
     * @param   DecrementVoteService    $decrementVoteService
     * @param   RouteParser             $routeParser
     */
    public function __construct(
        private VoteRepository $voteRepository,
        private ValidationService $validationService,
        private CreateVoteService $createVoteService,
        private DeleteVoteService $deleteVoteService,
        private IncrementVoteService $incrementVoteService,
        private DecrementVoteService $decrementVoteService,
        private RouteParser $routeParser
    ) {}

    /**
     * Create new vote.
     *
     *  @CR\Route(
     *     name="create",
     *     methods={"POST"},
     *     pattern="/create"
     * )
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws AuthenticationException
     * @throws DataObjectManagerException
     * @throws ValidationException
     * @throws VoteException
     * @throws NoSuchEntityException
     */
    public function create(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            VoteInterface::COLUMN_ENTITY_ID => 'required|numeric',
            VoteInterface::COLUMN_VOTE_ENTITY => 'required|numeric',
            VoteInterface::COLUMN_VOTE_TYPE => 'required|numeric'
        ]);

        /** @var User $user */
        $user = user($request);

        $customResponse = $this->createVoteService
            ->execute(
                $user->getId(),
                $parsedData
            );

        $result = $this->incrementVoteService
            ->execute(
                $parsedData['entity_id'],
                $parsedData['vote_entity'],
                $parsedData['vote_type'],
            );

        if (!$result) {
            $this->deleteVoteService->execute($user->getId(), $parsedData);
            throw new VoteException(
                __('The entity could not be incremented'),
                VoteResponseCodeInterface::RESPONSE_VOTE_ENTITY_COULD_NOT_BE_INCREMENTED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        return $this->respond(
            $response,
            response()
                ->setData([
                    'replacepage'  => $this->routeParser->urlFor('home'),
                    'status'    => 'success',
                    'message'   => __('You\'ve succesfully voted!')
                ])
        );
    }

    /**
     * Delete vote.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws AuthenticationException
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     * @throws ValidationException
     * @throws VoteException
     */
    public function delete(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            VoteInterface::COLUMN_ENTITY_ID => 'required|numeric',
            VoteInterface::COLUMN_VOTE_ENTITY => 'required|numeric',
            VoteInterface::COLUMN_VOTE_TYPE => 'required|numeric'
        ]);

        /** @var User $user */
        $user = user($request);

        $customResponse = $this->deleteVoteService->execute($user->getId(), $parsedData);

        if (!$customResponse->getData()) {
            throw new VoteException(
                __('Vote could not be deleted'),
                VoteResponseCodeInterface::RESPONSE_VOTE_ENTITY_NOT_DELETED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        $result = $this->decrementVoteService
            ->execute(
                $parsedData['entity_id'],
                $parsedData['vote_entity'],
                $parsedData['vote_type'],
            );

        if (!$result) {
            $this->createVoteService->execute($user->getId(), $parsedData);
            throw new VoteException(
                __('The entity could not be incremented'),
                VoteResponseCodeInterface::RESPONSE_VOTE_ENTITY_COULD_NOT_BE_INCREMENTED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        return $this->respond(
            $response,
            $customResponse
        );
    }
}
