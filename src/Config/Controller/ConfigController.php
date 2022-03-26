<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Config\Controller;

use Ares\Framework\Controller\BaseController;
use PHLAK\Config\Config;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class ConfigController
 *
 * @package Ares\Config\Controller
 */
class ConfigController extends BaseController
{
    /**
     * ConfigController constructor.
     * @param Config $config
     */
    public function __construct(
        private Config $config
    ) {}

    /**
     * Responds Config
     *e
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response): Response
    {
        return $this->respond(
            $response,
            response()->setData(
                $this->config->get('hotel_settings'))
        );
    }
}