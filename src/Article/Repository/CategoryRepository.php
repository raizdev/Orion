<?php declare(strict_types=1);
namespace Orion\Article\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Repository\BaseRepository;
use Ares\Framework\Model\Query\Collection;
use Ares\Framework\Model\Query\PaginatedCollection;
use Orion\Article\Entity\Category;

/**
 * Class CategoryRepository
 *
 * @package Orion\Article\Repository
 */
class CategoryRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_ARTICLE_CATEGORY';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_ARTICLE_CATEGORY_COLLECTION_';

    /** @var string */
    protected string $entity = Category::class;

    /**
     * @param string $term
     * @param int    $page
     * @param int    $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function articleCategories(string $term, int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select([
                'ares_articles_categories.id', 'ares_articles_categories.name', 'ares_articles_categories.description'
            ])->where('hidden', 0)
            ->groupBy('ares_articles_categories.id');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }
}
