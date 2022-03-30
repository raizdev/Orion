<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

/** @TODO change error reporting when package updates are done */
error_reporting(E_ALL ^ E_DEPRECATED);

(require __DIR__ . '/../app/bootstrap.php')->run();
