<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Tag\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Tag\Exception\TagException;
use Ares\Tag\Interfaces\Response\TagResponseCodeInterface;
use Ares\Tag\Repository\TagRepository;
use Slim\Routing\RouteParser;

/**
 * Class DeleteTagService
 *
 * @package Ares\Tag\Service
 */
class DeleteTagService
{
    /**
     * DeleteTagService constructor.
     *
     * @param TagRepository $tagRepository
     */
    public function __construct(
        private TagRepository $TagRepository,
        private RouteParser $routeParser
    ) {}

    /**
     * @param int $id
     *
     * @return CustomResponseInterface
     * @throws TagException
     * @throws DataObjectManagerException
     */
    public function execute(int $userId, array $data): CustomResponseInterface
    {
        $tag = $this->TagRepository->get($data['id'], 'id');
        
        if($tag->getUserId() !== $userId) {
            throw new TagException(
                __('Tag could not be deleted'),
                TagResponseCodeInterface::RESPONSE_TAG_NOT_DELETED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            ); 
        }

        $deleted = $this->TagRepository->delete($data["id"]);

        if (!$deleted) {
            throw new TagException(
                __('Tag could not be deleted'),
                TagResponseCodeInterface::RESPONSE_TAG_NOT_DELETED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        return response()->setData([
            'replacepage'  => $this->routeParser->urlFor('community_home'),
            'status'    => 'success',
            'message'   => __('Tag deleted!'),
        ]);
    }
}
