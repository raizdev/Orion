<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Article\Service;

use Ares\Article\Exception\ArticleException;
use Ares\Article\Interfaces\Response\ArticleResponseCodeInterface;
use Ares\Article\Repository\ArticleRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;

/**
 * Class DeleteArticleService
 *
 * @package Ares\Article\Service
 */
class DeleteArticleService
{
    /**
     * DeleteArticleService constructor.
     *
     * @param ArticleRepository $articleRepository
     */
    public function __construct(
        private ArticleRepository $articleRepository
    ) {}

    /**
     * @param int $id
     *
     * @return CustomResponseInterface
     * @throws ArticleException
     * @throws DataObjectManagerException
     */
    public function execute(int $id): CustomResponseInterface
    {
        $deleted = $this->articleRepository->delete($id);

        if (!$deleted) {
            throw new ArticleException(
                __('Article could not be deleted'),
                ArticleResponseCodeInterface::RESPONSE_ARTICLE_NOT_DELETED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        return response()
            ->setData(true);
    }
}
