<?php
namespace Orion\Article\Service;

use Orion\Article\Entity\Article;
use Orion\Article\Entity\Contract\ArticleInterface;
use Orion\Article\Exception\ArticleException;
use Orion\Article\Interfaces\Response\ArticleResponseCodeInterface;
use Orion\Article\Repository\ArticleRepository;
use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Exception\NoSuchEntityException;
use Orion\Core\Interfaces\CustomResponseInterface;
use Orion\Core\Interfaces\HttpResponseCodeInterface;
use Cocur\Slugify\Slugify;

/**
 * Class EditArticleService
 *
 * @package Ares\Article\Service
 */
class EditArticleService
{
    /**
     * EditArticleService constructor.
     *
     * @param ArticleRepository $articleRepository
     * @param Slugify           $slug
     */
    public function __construct(
        private ArticleRepository $articleRepository,
        private Slugify $slug
    ) {}

    /**
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException|ArticleException
     */
    public function execute(array $data): CustomResponseInterface
    {
        /** @var int $articleId */
        $articleId = $data['id'];

        /** @var Article $article */
        $article = $this->articleRepository->get($articleId, ArticleInterface::COLUMN_ID, false, false);
        
        /** @var Article $existingArticle */
        $existingArticle = $this->articleRepository->getExistingArticle($article->getTitle(), $article->getSlug());

        if ($existingArticle && $existingArticle->getId() !== $article->getId()) {

            throw new ArticleException(
                __('Article with given Title already exists'),
                ArticleResponseCodeInterface::RESPONSE_ARTICLE_TITLE_EXIST,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        $article = $this->getEditedArticle($article, $data);

        /** @var Article $article */
        $article = $this->articleRepository->save($article);

        return response()
            ->setData($article);
    }

    /**
     * @param Article $article
     * @param array   $data
     *
     * @return Article
     */
    private function getEditedArticle(Article $article, array $data): Article
    {
        return $article
            ->setTitle($data['title'] ?: $article->getTitle())
            ->setSlug($this->slug->slugify($data['title']))
            ->setDescription($data['description'] ?: $article->getDescription())
            ->setContent($data['content'] ?: $article->getContent())
            ->setImage($data['image'] ?: $article->getImage())
            ->setThumbnail($data['thumbnail'] ?: $article->getThumbnail())
            ->setHidden($data['hidden']) //0 ?: 1 will always be 1
            ->setPinned($data['pinned'])
            ->setUpdatedAt(new \DateTime());
    }
}
