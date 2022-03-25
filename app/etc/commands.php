<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

// Returns all commands added into our application
return [
    \Ares\Framework\Command\ClearCacheCommand::class,
    \Cosmic\Core\Command\Commands\MinifyAll::class,
    \Cosmic\Core\Command\Commands\MinifyCss::class,
    \Cosmic\Core\Command\Commands\MinifyJs::class
];
