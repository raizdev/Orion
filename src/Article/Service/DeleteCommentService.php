<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Article\Service;

use Ares\Article\Exception\CommentException;
use Ares\Article\Interfaces\Response\ArticleResponseCodeInterface;
use Ares\Article\Repository\CommentRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;

/**
 * Class DeleteArticleService
 *
 * @package Ares\Article\Service
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
                ArticleResponseCodeInterface::RESPONSE_ARTICLE_COMMENT_NOT_DELETED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        return response()
            ->setData(true);
    }
}
