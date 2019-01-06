<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Offer;
use ApiBundle\Exception\UserException;
use ApiBundle\Utils\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws UserException
     */
    public final function addAction(Request $request)
    {
        $post = $request->request->all();
        $offer = $this->offerService->addOffer($post);

        return new JsonResponse(["added" => true, "id" => $offer->getId()]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws UserException
     * @throws \ApiBundle\Exception\OfferException
     */
    public final function getEditDataAction(Request $request) {
        $data = $this->offerService->getEditData($request->get('id'));
        return new JsonResponse($data);
    }
}
