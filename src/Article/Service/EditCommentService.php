<?php
namespace Orion\Article\Service;

use Orion\Article\Entity\Comment;
use Orion\Article\Repository\CommentRepository;
use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Exception\NoSuchEntityException;
use Orion\Core\Interfaces\CustomResponseInterface;

/**
 * Class EditCommentService
 *
 * @package Orion\Article\Service
 */
class EditCommentService
{
    /**
     * EditCommentService constructor.
     *
     * @param CommentRepository $commentRepository
     */
    public function __construct(
        private CommentRepository $commentRepository
    ) {}

    /**
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     */
    public function execute(array $data): CustomResponseInterface
    {
        /** @var int $commentId */
        $commentId = $data['comment_id'];

        /** @var string $content */
        $content = $data['content'];

        /** @var Comment $comment */
        $comment = $this->commentRepository->get($commentId);

        $comment
            ->setContent($content)
            ->setIsEdited(1)
            ->setUpdatedAt(new \DateTime());

        /** @var Comment $comment */
        $comment = $this->commentRepository->save($comment);

        return response()
            ->setData($comment);
    }
}
