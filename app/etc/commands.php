<?php
// Returns all commands added into our application
return [
    \Orion\Core\Command\ClearCacheCommand::class,
    \Orion\Core\Command\Minifier\Commands\MinifyAll::class,
    \Orion\Core\Command\Minifier\Commands\MinifyCss::class,
    \Orion\Core\Command\Minifier\Commands\MinifyJs::class
];
