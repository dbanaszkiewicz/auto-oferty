<?php
/**
 * Created by PhpStorm.
 * User: dbanaszkiewicz
 * Date: 27.10.18
 * Time: 13:36
 */

namespace ApiBundle\Controller;

use ApiBundle\Exception\ApiException;
use ApiBundle\Exception\UserException;
use ApiBundle\Utils\Controller;
use Symfony\Component\HttpFoundation\Request;

class ErrorController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function actionNotFoundAction(Request $request)
    {
        throw ApiException::methodNotExists();
    }
}
