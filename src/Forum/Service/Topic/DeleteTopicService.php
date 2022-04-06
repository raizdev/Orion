<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Forum\Service\Topic;

use Ares\Forum\Exception\TopicException;
use Ares\Forum\Interfaces\Response\ForumResponseCodeInterface;
use Ares\Forum\Repository\TopicRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;

/**
 * Class DeleteTopicService
 *
 * @package Ares\Forum\Service\Topic
 */
class DeleteTopicService
{
    /**
     * DeleteTopicService constructor.
     *
     * @param TopicRepository $topicRepository
     */
    public function __construct(
        private TopicRepository $topicRepository
    ) {}

    /**
     * @param int $id
     *
     * @return CustomResponseInterface
     * @throws TopicException
     * @throws DataObjectManagerException
     */
    public function execute(int $id): CustomResponseInterface
    {
        $deleted = $this->topicRepository->delete($id);

        if (!$deleted) {
            throw new TopicException(
                __('Topic could not be deleted'),
                ForumResponseCodeInterface::RESPONSE_FORUM_TOPIC_NOT_DELETED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        return response()
            ->setData(true);
    }
}
