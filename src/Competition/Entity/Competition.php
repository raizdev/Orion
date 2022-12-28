<?php declare(strict_types=1);
namespace Orion\Competition\Entity;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Orion\Competition\Entity\Contract\CompetitionInterface;
use Orion\Competition\Repository\CompetitionRepository;
use Orion\User\Entity\User;

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

        /**
     * @return int
     */
    public function getToTimestamp(): int
    {
        return $this->getData(CompetitionInterface::COLUMN_TO_TIMESTAMP);
    }

    /**
     * @param int $toTimestamp
     *
     * @return UserOfTheHotel
     */
    public function setToTimestamp(int $toTimestamp): UserOfTheHotel
    {
        return $this->setData(CompetitionInterface::COLUMN_TO_TIMESTAMP, $toTimestamp);
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->getData(CompetitionInterface::COLUMN_CREATED_AT);
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return UserOfTheHotel
     */
    public function setCreatedAt(\DateTime $createdAt): UserOfTheHotel
    {
        return $this->setData(CompetitionInterface::COLUMN_CREATED_AT, $createdAt);
    }
}
