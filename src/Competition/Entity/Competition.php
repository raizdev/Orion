<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Competition\Entity;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Ares\Competition\Entity\Contract\CompetitionInterface;
use Ares\Competition\Repository\CompetitionRepository;
use Ares\User\Entity\User;

/**
 * Class Tag
 *
 * @package Ares\Competition\Entity
 */
class Competition extends DataObject implements CompetitionInterface
{
    /** @var string */
    public const TABLE = 'ares_competitions';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(CompetitionInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Competition
     */
    public function setId(int $id): Competition
    {
        return $this->setData(CompetitionInterface::COLUMN_ID, $id);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->getData(CompetitionInterface::COLUMN_TITLE);
    }

    /**
     * @param int $title
     *
     * @return Tag
     */
    public function setTitle(int $title): Competition
    {
        return $this->setData(CompetitionInterface::COLUMN_TITLE, $title);
    }

    /**
     * @return string
     */
    public function getShortTitle(): string
    {
        return $this->getData(CompetitionInterface::COLUMN_SHORT_TITLE);
    }

    /**
     * @param int $shortTitle
     *
     * @return Tag
     */
    public function setShortTitle(string $shortTitle): Competition
    {
        return $this->setData(CompetitionInterface::COLUMN_SHORT_TITLE, $shortTitle);
    }

    /**
     * @return string
     */
    public function getIframe(): string
    {
        return $this->getData(CompetitionInterface::COLUMN_IFRAME);
    }

    /**
     * @param int $iframe
     *
     * @return Tag
     */
    public function setIframe(string $iframe): Competition
    {
        return $this->setData(CompetitionInterface::COLUMN_IFRAME, $iframe);
    }

    /**
     * @return string
     */
    public function getHeader(): string
    {
        return $this->getData(CompetitionInterface::COLUMN_HEADER);
    }

    /**
     * @param int $header
     *
     * @return Tag
     */
    public function setHeader(string $header): Competition
    {
        return $this->setData(CompetitionInterface::COLUMN_HEADER, $header);
    }
}
