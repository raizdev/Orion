<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

namespace App\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Monolog\Logger;

/**
 * Class ConfigServiceProvider
 *
 * @package App\Provider
 */
class ConfigServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'settings'
    ];

    public function register()
    {
        $container = $this->getContainer();

        $container->add('settings', function () {
            return [
                'doctrine' => [
                    'dev_mode' => getenv('DB_DEV_MODE'),

                    'cache_dir' => '../app/Cache/doctrine',

                    'metadata_dirs' => ['src/Entity'],

                    'connection' => [
                        'driver'   => getenv('DB_DRIVER'),
                        'host'     => getenv('DB_HOST'),
                        'port'     => getenv('DB_PORT'),
                        'dbname'   => getenv('DB_NAME'),
                        'user'     => getenv('DB_USER'),
                        'password' => getenv('DB_PASS'),
                        'charset'  => 'utf8mb4'
                    ]
                ],

                'jwt' => [
                    // The issuer name
                    'issuer'      => getenv('WEB_NAME'),

                    // Max lifetime in seconds
                    'lifetime'    => getenv('WEB_TOKEN_DURATION'),

                    // The private key
                    'private_key' => getenv('WEB_SECRET_PRIVATE'),

                    'public_key'  => getenv('WEB_SECRET_PUBLIC')
                ],

                'logger' => [
                    'name'               => getenv('WEB_NAME') . '-event-log',
                    'enabled_log_levels' => [
                        // DEBUG
                        [
                            'path'  => __DIR__ . '../../app/Logs/info.log',
                            'level' => Logger::DEBUG
                        ],
                        // INFO
                        [
                            'path'  => __DIR__ . '../../app/Logs/info.log',
                            'level' => Logger::INFO
                        ],
                        // NOTICE
                        [
                            'path'  => __DIR__ . '../../app/Logs/info.log',
                            'level' => Logger::NOTICE
                        ],
                        // WARNING
                        [
                            'path'  => __DIR__ . '../../app/Logs/warning.log',
                            'level' => Logger::WARNING
                        ],
                        // ERROR
                        [
                            'path'  => __DIR__ . '../../app/Logs/error.log',
                            'level' => Logger::ERROR
                        ],
                        // CRITICAL
                        [
                            'path'  => __DIR__ . '../../app/Logs/critical.log',
                            'level' => Logger::CRITICAL
                        ],
                        // ALERT
                        [
                            'path'  => __DIR__ . '../../app/Logs/critical.log',
                            'level' => Logger::ALERT
                        ],
                        // EMERGENCY
                        [
                            'path'  => __DIR__ . '../../app/Logs/critical.log',
                            'level' => Logger::EMERGENCY
                        ],
                    ],
                ]
            ];
        });
    }
}