<?php
namespace Orion\Article\Service;

use Orion\Article\Entity\Article;
use Orion\Article\Exception\ArticleException;
use Orion\Article\Interfaces\Response\ArticleResponseCodeInterface;
use Orion\Article\Repository\ArticleRepository;
use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Exception\NoSuchEntityException;
use Orion\Core\Interfaces\CustomResponseInterface;
use Orion\Core\Interfaces\HttpResponseCodeInterface;
use Cocur\Slugify\Slugify;

/**
 * Class CreateArticleService
 *
 * @package Ares\Article\Service
 */
class CreateArticleService
{
    /**
     * CreateArticleService constructor.
     *
     * @param ArticleRepository $articleRepository
     * @param Slugify           $slug
     */
    public function __construct(
        private ArticleRepository $articleRepository,
        private Slugify $slug
    ) {}

    /**
     * Creates new article with given data.
     *
     * @param int   $userId
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws ArticleException
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     */
    public function execute(int $userId, array $data): CustomResponseInterface
    {
        $article = $this->getNewArticle($userId, $data);

        /** @var Article $existingArticle */
        $existingArticle = $this->articleRepository->getExistingArticle($article->getTitle(), $article->getSlug());

        if ($existingArticle) {
            throw new ArticleException(
                __('Article with given Title and Slug already exists'),
                ArticleResponseCodeInterface::RESPONSE_ARTICLE_TITLE_EXIST,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        /** @var Article $article */
        $article = $this->articleRepository->save($article);

        return response()
            ->setData($article);
    }

    /**
     * Returns new article.
     *
     * @param int   $userId
     * @param array $data
     *
     * @return Article
     */
    private function getNewArticle(int $userId, array $data): Article
    {
        $article = new Article();

        return $article
            ->setTitle($data['title'])
            ->setSlug($this->slug->slugify($data['title']))
            ->setDescription($data['description'])
            ->setContent($data['content'])
            ->setImage($data['image'])
            ->setThumbnail($data['thumbnail'])
            ->setAuthorId($userId)
            ->setHidden($data['hidden'])
            ->setPinned($data['pinned'])
            ->setLikes(0)
            ->setDislikes(0)
            ->setCreatedAt(new \DateTime());
    }
}
