<?php

namespace ApiBundle\Controller;

use ApiBundle\Exception\UserException;
use ApiBundle\Utils\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class User
 * @package ApiBundle\Service
 */
class UserController extends Controller
{
    /**
     * @var \ApiBundle\Service\User
     */
    private $userService = null;
    protected function doSomeStuff()
    {
        parent::doSomeStuff();
        $this->userService = $this->get('user_service');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws UserException
     * @throws \ApiBundle\Exception\ApiException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public final function loginAction(Request $request)
    {
        $this->userService->logout();
        $response = new Response();
        $response->headers->clearCookie("sid");
        $response->sendHeaders();

        $email = $request->request->get('email', null);
        $password = $request->request->get('password', null);

        if (empty($email)) {
            throw UserException::invalidEmail();
        } elseif (empty($password)) {
            throw UserException::invalidPassword();
        } else {
            $login = $this->userService->login($email, $password);
            $response = new Response();
            $response->headers->setCookie(
                new Cookie('sid', $login, time() + 60 * $this->userService->expireSessionTime, '/', null, null, false)
            );
            $response->sendHeaders();

            return new JsonResponse(["login" => true]);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public final function getUserInfoAction(Request $request)
    {
        $userInfo = $this->userService->getUserInfo();
        if ($userInfo['isLogged']) {
            $response = new Response();
            $c = new Cookie('sid', $request->cookies->get("sid"),
                time() + 60 * $this->userService->expireSessionTime);
            $response->headers->setCookie($c);
            $response->sendHeaders();
        }

        return new JsonResponse(["UserInfoResult" => $userInfo]);
    }

    /**
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public final function logoutAction()
    {
        $this->userService->logout();
        $response = new Response();
        $response->headers->clearCookie("sid");
        $response->sendHeaders();

        return new JsonResponse(["logout" => true]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public final function registerAction(Request $request)
    {
        $email = $request->request->get("email", null);
        $password = $request->request->get("password", null);
        $firstName = $request->request->get("firstName", null);

        $this->userService->register($email, $password, $firstName);

        return new JsonResponse(["registered" => true]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \ApiBundle\Exception\ApiException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public final function changePassAction(Request $request)
    {
        $newpass = $request->request->get("newpass", null);
        $oldpass = $request->request->get("oldpass", null);

        $this->userService->changePass($newpass, $oldpass);

        return new JsonResponse(["changed" => true]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws UserException
     * @throws UserException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public final function changeDataAction(Request $request)
    {
        $post = $request->request->all();
        $this->userService->editUser($post);

        return new JsonResponse(["updated" => true]);
    }

    public final function  getOfferListAction(Request $request)
    {
        $userInfo = $this->userService->getUserInfo();
        if ($userInfo['isLogged']) {
            $response = new Response();
            $c = new Cookie('sid', $request->cookies->get("sid"),
                time() + 60 * $this->userService->expireSessionTime);
            $response->headers->setCookie($c);
            $response->sendHeaders();
        }
        $offerInfo = $this->userService->getOfferListByUserId();

        return new JsonResponse(["OfferListByUserIdResult" => $offerInfo]);
    }
}
