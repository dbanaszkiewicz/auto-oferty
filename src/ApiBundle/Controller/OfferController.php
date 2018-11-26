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
class OfferController extends Controller
{
    /**
     * @var \ApiBundle\Service\Offer
     */
    private $offerService = null;

    protected function doSomeStuff()
    {
        parent::doSomeStuff();
        $this->offerService = $this->get('offer_service');
    }

    public final function addAction(Request $request)
    {
        $post = $request->request->all();
        $this->offerService->addOffer($post);

        return new JsonResponse(["added" => true]);
    }
}
