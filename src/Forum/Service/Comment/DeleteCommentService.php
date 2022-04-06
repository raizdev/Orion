<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Forum\Service\Comment;

use Ares\Forum\Exception\CommentException;
use Ares\Forum\Interfaces\Response\ForumResponseCodeInterface;
use Ares\Forum\Repository\CommentRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;

/**
 * Class DeleteCommentService
 *
 * @package Ares\Forum\Service\Comment
 */
class DeleteCommentService
{
    /**
     * DeleteCommentService constructor.
     *
     * @param CommentRepository $commentRepository
     */
    public function __construct(
        private CommentRepository $commentRepository
    ) {}

    /**
     * @param int $id
     *
     * @return CustomResponseInterface
     * @throws CommentException
     * @throws DataObjectManagerException
     */
    public function execute(int $id): CustomResponseInterface
    {
        $deleted = $this->commentRepository->delete($id);

        if (!$deleted) {
            throw new CommentException(
                __('Comment could not be deleted'),
                ForumResponseCodeInterface::RESPONSE_FORUM_COMMENT_NOT_DELETED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        return response()
            ->setData(true);
    }
}
