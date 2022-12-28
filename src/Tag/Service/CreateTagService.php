<?php
namespace Orion\Tag\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Framework\Model\DataObject;
use Orion\Tag\Entity\Tag;
use Orion\Tag\Exception\TagException;
use Orion\Tag\Interfaces\Response\TagResponseCodeInterface;
use Orion\Tag\Repository\TagRepository;
use Slim\Routing\RouteParser;

/**
 * Class TagVoteService
 *
 * @package Ares\Tag\Service
 */
class CreateTagService
{
    /**
     * CreateTagService constructor.
     *
     * @param TagRepository $tagRepository
     */
    public function __construct(
        private TagRepository $tagRepository,
        private RouteParser $routeParser
    ) {}

    /**
     * Create new tag with given data.
     *
     * @param int   $userId
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws TagException
     * @throws NoSuchEntityException
     */
    public function execute(int $userId, array $data): CustomResponseInterface
    {
        $tag = $this->getNewTag($userId, $data);

        $existingTag = $this->tagRepository->getExistingTag($tag, $userId);

        if ($existingTag) {
            throw new VoteException(
                __('User already have this tag'),
                TagResponseCodeInterface::RESPONSE_TAG_ENTITY_ALREADY_EXISTS,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        $tag = $this->tagRepository->save($tag);

        return response()->setData([
            'replacepage'  => $this->routeParser->urlFor('community_home'),
            'status'    => 'success',
            'message'   => __('Tag created!'),
        ]);
    }

    /**
     * Return new tag.
     *
     * @param int   $userId
     * @param array $data
     *
     * @return Tag
     */
    private function getNewTag(int $userId, array $data): Tag
    {
        $tag = new Tag();

        return $tag
            ->setUserId($userId)
            ->setTag($data['tag']);
    }
}