<?php


namespace ApiBundle\Controller;

use ApiBundle\Exception\ExceptionHandler;
use ApiBundle\Utils\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class UserController extends Controller
{

    /**
     * @var \ApiBundle\Service\User
     */
    private $userManager = null;

    protected function doSomeStuff()
    {
        parent::doSomeStuff();
        $this->userManager = $this->get('user_manager');
    }
}