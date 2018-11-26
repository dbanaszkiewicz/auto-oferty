<?php

namespace ApiBundle\Service;

use ApiBundle\Exception\ApiException;
use ApiBundle\Exception\OfferException;
use Doctrine\ORM\EntityManager;
use ApiBundle\Entity\Session;
use ApiBundle\Exception\UserException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class User
 * @package ApiBundle\Service
 */
class Offer
{

    /**
     * @var EntityManager
     */
    private $em = null;

    /**
     * @var User
     */
    private $userService = null;
  /**
     * User constructor.
     * @param EntityManager $entityManager
     * @param PHPass $PhpassManager
     */
    public function __construct(EntityManager $entityManager, User $userService)
    {
        $this->em = $entityManager;
        $this->userService = $userService;
    }

    /**
     * @param $post
     * @throws OfferException
     */
    public function addOffer($post)
    {
        if(!$this->userService->isLogged)
        {
            throw  UserException::userIsNotLogged();
        }
        $offer = new \ApiBundle\Entity\Offer();

        $offer->setDoors($post['doors']);
        $offer->setName($post['name']);
        $offer->setPrice($post['price']);
        $offer->setAfterAccident($post['afterAccident']);
        $offer->setUsed($post['used']);
        $offer->setDescription($post['description']);
        $offer->setFuelType($post['fuelType']);
        $offer->setEnginePower($post['enginePower']);
        $offer->setEngineCapacity($post['engineCapacity']);
        $offer->setMeterStatus($post['meterStatus']);
        $offer->setGearbox($post['gearbox']);
        $offer->setProductionYear($post['productionYear']);
        $offer->setBodyColor($post['bodyColor']);
        $offer->setBodyType($post['bodyType']);
        $offer->setUser($this->userService->userEntity);

        $version = $this->em->getRepository('ApiBundle:Version')->findOneBy(['id' => $post['version']]);
        if(!$version)
        {
            throw OfferException::invalidVersion();
        }
        $offer->setVersion($version);

        foreach($post['equipments'] as $equipmentId)
        {
            $equipment = $this->em->getRepository('ApiBundle:Equipment')->findOneBy(['id' => $equipmentId]);
            if(!$equipment)
            {
                throw OfferException::invalidEquipment();
            }
            $offer->addEqipment($equipment);
        }

        $this->em->persist($offer);
        $this->em->flush();
    }
}
