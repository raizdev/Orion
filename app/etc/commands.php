<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

// Returns all commands added into our application
return [
    \Ares\Framework\Command\ClearCacheCommand::class,
    \Cosmic\Core\Command\Minifier\Commands\MinifyAll::class,
    \Cosmic\Core\Command\Minifier\Commands\MinifyCss::class,
    \Cosmic\Core\Command\Minifier\Commands\MinifyJs::class
];
