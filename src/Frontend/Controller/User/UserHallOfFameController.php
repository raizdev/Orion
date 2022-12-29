<?php
namespace Orion\Frontend\Controller\User;

use Orion\User\Entity\User;
use Orion\User\Entity\UserCurrency;
use Orion\User\Entity\UserSetting;
use Orion\Core\Mapping\Annotation as CR;
use Orion\Core\Controller\BaseController;
use Orion\Core\Exception\DataObjectManagerException;
use Orion\User\Repository\UserCurrencyRepository;
use Orion\User\Repository\UserRepository;
use Orion\User\Repository\UserSettingRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class UserHallOfFameController
 *
 * @package Ares\Frontend\Controller\User
 * @CR\Router
 */
class UserHallOfFameController extends BaseController
{
    /**
     * UserHallOfFameController constructor.
     *
     * @param Twig                   $twig
     * @param UserRepository         $userRepository
     * @param UserSettingRepository  $userSettingRepository
     * @param UserCurrencyRepository $userCurrencyRepository
     */
    public function __construct(
        private Twig $twig,
        private UserRepository $userRepository,
        private UserSettingRepository $userSettingRepository,
        private UserCurrencyRepository $userCurrencyRepository
    ) {}

    /**
     * @CR\Route(
     *     name="community-ranking",
     *     methods={"GET"},
     *     pattern="/community/ranking"
     * )
     *
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     *
     * @throws DataObjectManagerException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */

    public function __invoke(Request $request, Response $response): Response
    {
        /** @var UserSetting $online */
        $online = $this->userSettingRepository->getTopOnlineTime();

        /** @var UserSetting $achievements */
        $achievements = $this->userSettingRepository->getTopAchievements();

        /** @var UserSetting $respects */
        $respects = $this->userSettingRepository->getTopRespects();

        /** @var UserCurrency $diamonds */
        $diamonds = $this->userCurrencyRepository->getTopDiamonds();

        /** @var UserCurrency $duckets */
        $duckets = $this->userCurrencyRepository->getTopDuckets();

        /** @var User $credits */
        $credits = $this->userRepository->getTopCredits();

        return $this->twig->render($response, 'Frontend/Views/pages/community/highscore.twig', [
            'online' => $online,
            'achievements' => $achievements,
            'diamonds' => $diamonds,
            'duckets' => $duckets,
            'credits' => $credits,
            'respects' => $respects,
            'page' => 'games_ranking'
        ]);
    }
}
