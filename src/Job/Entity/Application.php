<?php declare(strict_types=1);
namespace Orion\Job\Entity;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Model\DataObject;
use Orion\Job\Entity\Contract\ApplicationInterface;
use Orion\Job\Repository\ApplicationRepository;
use Orion\User\Entity\User;
use Orion\Job\Entity\Job;
use Orion\Job\Repository\JobRepository;
use Orion\User\Repository\UserRepository;

/**
 * Class Tag
 *
 * @package Orion\Job\Entity
 */
class Application extends DataObject implements ApplicationInterface
{
    /** @var string */
    public const TABLE = 'ares_jobs_applies';

    /*** @var array */
    public const RELATIONS = [
        'user' => 'getUser',
        'job'  => 'getJob'
      ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(ApplicationInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Job
     */
    public function setId(int $id): Application
    {
        return $this->setData(ApplicationInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getJobId(): int
    {
        return $this->getData(ApplicationInterface::COLUMN_JOB_ID);
    }

    /**
     * @param int $job
     *
     * @return Application
     */
    public function setJobId(int $jobId): Application
    {
        return $this->setData(ApplicationInterface::COLUMN_JOB_ID, $jobId);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->getData(ApplicationInterface::COLUMN_USER_ID);
    }

    /**
     * @param int $userId
     *
     * @return Application
     */
    public function setUserId(int $userId): Application
    {
        return $this->setData(ApplicationInterface::COLUMN_USER_ID, $userId);
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->getData(ApplicationInterface::COLUMN_FIRSTNAME);
    }

    /**
     * @param int $firstName
     *
     * @return Application
     */
    public function setFirstname(string $firstName): Application
    {
        return $this->setData(ApplicationInterface::COLUMN_FIRSTNAME, $firstName);
    }

    /**
     * @return string
     */
    public function getDiscord(): string
    {
        return $this->getData(ApplicationInterface::COLUMN_DISCORD);
    }

    /**
     * @param int $discord
     *
     * @return Application
     */
    public function setDiscord(string $discord): Application
    {
        return $this->setData(ApplicationInterface::COLUMN_DISCORD, $discord);
    }

    /**
     * @return string
     */
    public function getAge(): int
    {
        return $this->getData(JobInterface::COLUMN_AGE);
    }

    /**
     * @param int $age
     *
     * @return ApplicationInterface
     */
    public function setAge(int $age): Application
    {
        return $this->setData(ApplicationInterface::COLUMN_AGE, $age);
    }

    /**
     * @return int
     */
    public function getWeeklyHours(): int
    {
        return $this->getData(ApplicationInterface::COLUMN_WEEKLY_HOURS);
    }

    /**
     * @param int $weeklyHours
     *
     * @return Application
     */
    public function setWeeklyHours(int $weeklyHours): Application
    {
        return $this->setData(ApplicationInterface::COLUMN_WEEKLY_HOURS, $weeklyHours);
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->getData(ApplicationInterface::COLUMN_COUNTRY);
    }

    /**
     * @param int $country
     *
     * @return Application
     */
    public function setCountry(string $country): Application
    {
        return $this->setData(ApplicationInterface::COLUMN_COUNTRY, $country);
    }

    /**
     * @return string
     */
    public function getWhyUs(): string
    {
        return $this->getData(ApplicationInterface::COLUMN_WHY_US);
    }

    /**
     * @param int $whyUs
     *
     * @return Application
     */
    public function setWhyUs(string $whyUs): Application
    {
        return $this->setData(ApplicationInterface::COLUMN_WHY_US, $whyUs);
    }

    /**
     * @return string
     */
    public function getIdeas(): string
    {
        return $this->getData(ApplicationInterface::COLUMN_IDEAS);
    }

    /**
     * @param int $ideas
     *
     * @return Application
     */
    public function setIdeas(string $ideas): Application
    {
        return $this->setData(ApplicationInterface::COLUMN_IDEAS, $ideas);
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->getData(ApplicationInterface::COLUMN_STATUS);
    }

    /**
     * @param int $shortDescription
     *
     * @return Job
     */
    public function setStatus(string $status): Job
    {
        return $this->setData(ApplicationInterface::COLUMN_STATUS, $status);
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->getData(ApplicationInterface::COLUMN_CREATED_AT);
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return Job
     */
    public function setCreatedAt(\DateTime $createdAt): Job
    {
        return $this->setData(ApplicationInterface::COLUMN_CREATED_AT, $createdAt);
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->getData(ApplicationInterface::COLUMN_UPDATED_AT);
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return Job
     */
    public function setUpdatedAt(\DateTime $updatedAt): Job
    {
        return $this->setData(ApplicationInterface::COLUMN_UPDATED_AT, $updatedAt);
    }

    /**
     * @return User|null
     *
     * @throws DataObjectManagerException
     */
    public function getUser(): ?User
    {
        /** @var User $user */
        $user = $this->getData('user');

        if ($user) {
            return $user;
        }

        if (!isset($this)) {
            return null;
        }

        /** @var Application $applicationRepository */
        $applicationRepository = repository(ApplicationRepository::class);

        /** @var UserRepository $userRepository */
        $userRepository = repository(UserRepository::class);

        /** @var User $user */
        $user = $applicationRepository->getOneToOne(
            $userRepository,
            $this->getUserId(),
            'id'
        );

        if (!$user) {
            return null;
        }

        $this->setUser($user);

        return $user;
    }

    /**
     * @param User $user
     *
     * @return Application
     */
    public function setUser(User $user): Application
    {
        return $this->setData('user', $user);
    }

    /**
     * @return Job|null
     *
     * @throws DataObjectManagerException
     */
    public function getJob(): ?Job
    {
        /** @var Job $job */
        $job = $this->getData('job');

        if ($job) {
            return $job;
        }

        if (!isset($this)) {
            return null;
        }

        /** @var Application $applicationRepository */
        $applicationRepository = repository(ApplicationRepository::class);

        /** @var JobRepository $jobRepository */
        $jobRepository = repository(JobRepository::class);

        /** @var User $user */
        $job = $applicationRepository->getOneToOne(
            $jobRepository,
            $this->getJobId(),
            'id'
        );

        if (!$job) {
            return null;
        }

        $this->setJob($job);

        return $job;
    }

    /**
     * @param Job $job
     *
     * @return Application
     */
    public function setJob(Job $job): Application
    {
        return $this->setData('job', $job);
    }
}
