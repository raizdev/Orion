<?php
namespace Orion\Article\Service;

use Orion\Article\Exception\CommentException;
use Orion\Article\Interfaces\Response\ArticleResponseCodeInterface;
use Orion\Article\Repository\CommentRepository;
use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Interfaces\CustomResponseInterface;
use Orion\Core\Interfaces\HttpResponseCodeInterface;

/**
 * Class DeleteArticleService
 *
 * @package Orion\Article\Service
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
