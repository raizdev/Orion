<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Forum\Service\Thread;

use Ares\Forum\Exception\ThreadException;
use Ares\Forum\Interfaces\Response\ForumResponseCodeInterface;
use Ares\Forum\Repository\ThreadRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;

/**
 * Class DeleteThreadService
 *
 * @package Ares\Forum\Service\Thread
 */
class DeleteThreadService
{
    /**
     * DeleteThreadService constructor.
     *
     * @param ThreadRepository $threadRepository
     */
    public function __construct(
        private ThreadRepository $threadRepository
    ) {}

    /**
     * @param int $id
     *
     * @return CustomResponseInterface
     * @throws ThreadException
     * @throws DataObjectManagerException
     */
    public function execute(int $id): CustomResponseInterface
    {
        $deleted = $this->threadRepository->delete($id);

        if (!$deleted) {
            throw new ThreadException(
                __('Thread could not be deleted.'),
                ForumResponseCodeInterface::RESPONSE_FORUM_THREAD_NOT_DELETED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        return response()
            ->setData(true);
    }
}
