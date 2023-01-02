<?php declare(strict_types=1);
namespace Orion\Frontend\Controller\Auth;

use Orion\Ban\Exception\BanException;
use Orion\Core\Controller\BaseController;
use Orion\Core\Exception\AuthenticationException;
use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Exception\NoSuchEntityException;
use Orion\Core\Exception\ValidationException;
use Orion\Core\Service\ValidationService;
use Orion\User\Entity\Contract\UserInterface;
use Orion\User\Entity\User;
use Orion\User\Service\Auth\DetermineIpService;
use Orion\User\Service\Auth\LoginService;
use Orion\User\Service\Auth\RegisterService;
use Orion\User\Service\Auth\TicketService;
use Orion\Core\Mapping\Annotation as CR;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ReallySimpleJWT\Exception\ValidateException;

use Slim\Routing\RouteParser;
use Slim\Views\Twig;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use function __;
use function response;
use function user;

/**
 * Class AuthController
 *
 * @package Ares\Frontend\Controller\Auth
 * @CR\Router
 * @CR\Group(
 *     prefix="auth",
 *     pattern="auth"
 * )
 */
class AuthController extends BaseController
{
    /**
     * AuthController constructor.
     *
     * @param Twig $twig,
     * @param ValidationService $validationService
     * @param LoginService $loginService
     * @param RegisterService $registerService
     * @param TicketService $ticketService
     * @param DetermineIpService $determineIpService
     * @param RouteParser $routeParser
     * @param SessionInterface $session
     */
    public function __construct(
        private Twig $twig,
        private ValidationService $validationService,
        private LoginService $loginService,
        private RegisterService $registerService,
        private TicketService $ticketService,
        private DetermineIpService $determineIpService,
        private RouteParser $routeParser,
        private SessionInterface $session
    ) {}

    /**
     * AuthController Login Method
     *
     * @CR\Route(
     *     name="sign-in",
     *     methods={"POST"},
     *     pattern="/sign-in"
     * )
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response Returns a Response with the given Data
     * @throws BanException
     * @throws DataObjectManagerException
     * @throws ValidateException
     * @throws ValidationException
     * @throws NoSuchEntityException
     */
    public function login(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            UserInterface::COLUMN_USERNAME => 'required',
            UserInterface::COLUMN_PASSWORD => 'required'
        ]);

        /** @var string $determinedIp */
        $determinedIp = $this->determineIpService->execute();

        $parsedData[UserInterface::COLUMN_IP_CURRENT] = $determinedIp;

        $customResponse = $this->loginService->login($parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * AuthController Signup Method
     *
     * @CR\Route(
     *     name="sign-up",
     *     methods={"GET"},
     *     pattern="/sign-up"
     * )
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function signup(Request $request, Response $response): Response
    {
        return $this->twig->render($response,
            '/Frontend/Views/pages/auth/signup.twig', [
                'page' => 'registration'
            ]);
    }

    /**
     * Registers the User and parses a generated Token into the response
     *
     * @CR\Route(
     *     name="account-registration",
     *     methods={"POST"},
     *     pattern="/account-registration"
     * )
     *
     * @param Request $request
     * @param Response $response
     *
     * @return Response Returns a Response with the given Data
     * @throws \Exception
     */
    public function register(Request $request, Response $response): Response
    {

        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            UserInterface::COLUMN_USERNAME => 'required|min:2|max:12|regex:/^[a-zA-Z\d]+$/',
            UserInterface::COLUMN_MAIL => 'required|email|min:9',
            UserInterface::COLUMN_PASSWORD => 'required|min:6',
            'password_confirmation' => 'required|same:password'
        ]);

        /** @var string $determinedIp */
        $determinedIp = $this->determineIpService->execute();

        $parsedData[UserInterface::COLUMN_IP_CURRENT] = $determinedIp;
        $parsedData[UserInterface::COLUMN_IP_REGISTER] = $determinedIp;

        $customResponse = $this->registerService->register($parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * Gets a new Ticket for the current User
     *
     * @CR\Route(
     *     name="ticket",
     *     methods={"GET"},
     *     pattern="/ticket"
     * )
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws AuthenticationException
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     */
    public function ticket(Request $request, Response $response): Response
    {
        /** @var User $user */
        $user = user($request);

        /** @var TicketService $ticket */
        $ticket = $this->ticketService->generate($user);

        return $this->respond(
            $response,
            response()
                ->setData([
                    'ticket' => $ticket
                ])
        );
    }

    /**
     * Returns a response without the Authorization header
     * We could blacklist the token with redis-cache
     *
     * @CR\Route(
     *     name="logout",
     *     methods={"GET"},
     *     pattern="/logout"
     * )
     *
     * @param Request $request
     * @param Response $response
     *
     * @return Response Returns a Response with the given Data
     */
    public function logout(Request $request, Response $response): Response
    {
        $this->session->destroy();

        return $response->withHeader('Location', $this->routeParser->urlFor('home'));
    }
}
