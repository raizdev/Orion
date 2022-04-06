<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Guestbook\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Guestbook\Exception\GuestbookException;
use Ares\Guestbook\Interfaces\Response\GuestbookResponseCodeInterface;
use Ares\Guestbook\Repository\GuestbookRepository;

/**
 * Class DeleteGuestbookEntryService
 *
 * @package Ares\Guestbook\Service
 */
class DeleteGuestbookEntryService
{
    /**
     * DeleteGuestbookEntryService constructor.
     *
     * @param GuestbookRepository $guestbookRepository
     */
    public function __construct(
        private GuestbookRepository $guestbookRepository
    ) {}

    /**
     * @param int $id
     *
     * @return CustomResponseInterface
     * @throws GuestbookException
     * @throws DataObjectManagerException
     */
    public function execute(int $id): CustomResponseInterface
    {
        $deleted = $this->guestbookRepository->delete($id);

        if (!$deleted) {
            throw new GuestbookException(
                __('Guestbook entry could not be deleted'),
                GuestbookResponseCodeInterface::RESPONSE_GUESTBOOK_ENTRY_NOT_DELETED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        return response()
            ->setData(true);
    }
}
