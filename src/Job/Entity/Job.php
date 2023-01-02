<?php declare(strict_types=1);
namespace Orion\Job\Entity;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Model\DataObject;
use Orion\Job\Entity\Contract\JobInterface;
use Orion\Job\Repository\JobRepository;
use Orion\User\Entity\User;

/**
 * Class Tag
 *
 * @package Orion\Job\Entity
 */
class Job extends DataObject implements JobInterface
{
    /** @var string */
    public const TABLE = 'ares_jobs';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(JobInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Job
     */
    public function setId(int $id): Job
    {
        return $this->setData(JobInterface::COLUMN_ID, $id);
    }

    /**
     * @return string
     */
    public function getJob(): string
    {
        return $this->getData(JobInterface::COLUMN_JOB);
    }

    /**
     * @param int $job
     *
     * @return Job
     */
    public function setJob(int $job): Job
    {
        return $this->setData(JobInterface::COLUMN_JOB, $job);
    }

    /**
     * @return string
     */
    public function getFullDescription(): string
    {
        return $this->getData(JobInterface::COLUMN_FULL_DESCRIPTION);
    }

    /**
     * @param int $fullDescription
     *
     * @return Job
     */
    public function setFullDescription(string $fullDescription): Job
    {
        return $this->setData(JobInterface::COLUMN_FULL_DESCRIPTION, $fullDescription);
    }

    /**
     * @return string
     */
    public function getShortDescription(): string
    {
        return $this->getData(JobInterface::COLUMN_SHORT_DESCRIPTION);
    }

    /**
     * @param int $shortDescription
     *
     * @return Job
     */
    public function setShortDescription(string $shortDescription): Job
    {
        return $this->setData(JobInterface::COLUMN_SHORT_DESCRIPTION, $shortDescription);
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->getData(JobInterface::COLUMN_CREATED_AT);
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return Job
     */
    public function setCreatedAt(\DateTime $createdAt): Job
    {
        return $this->setData(JobInterface::COLUMN_CREATED_AT, $createdAt);
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->getData(JobInterface::COLUMN_UPDATED_AT);
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return Job
     */
    public function setUpdatedAt(\DateTime $updatedAt): Job
    {
        return $this->setData(JobInterface::COLUMN_UPDATED_AT, $updatedAt);
    }
}
