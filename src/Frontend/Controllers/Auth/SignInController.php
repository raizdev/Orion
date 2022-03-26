<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Frontend\Controllers\Auth;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class IndexController
 *
 * @package Ares\Core\Controller
 */
class SignInController extends BaseController
{
    /**
     * SignInController constructor.
     * @param Twig                  $twig
     */
    public function __construct(
        private Twig $twig
    ) {}

    /**
     * Responds to say hello to Twig
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError|DataObjectManagerException
     */
    public function index(Request $request, Response $response): Response
    {

        return $this->twig->render($response,
            '/Frontend/Views/pages/auth/signin.twig', [
            'page' => 'registration'
        ]);
    }
}