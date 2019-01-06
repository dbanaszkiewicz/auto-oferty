<?php

namespace ApiBundle\Service;

use ApiBundle\Entity\Equipment;
use ApiBundle\Exception\OfferException;
use Doctrine\ORM\EntityManager;
use ApiBundle\Exception\UserException;

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
     * @return \ApiBundle\Entity\Offer
     * @throws UserException
     */
    public function addOffer($post)
    {
        if(!$this->userService->isLogged)
        {
            throw  UserException::userIsNotLogged();
        }
        
        $this->em->beginTransaction();
        try {
            if (isset($post['id'])) {
                /**
                 * @var $offer \ApiBundle\Entity\Offer
                 */
                $offer = $this->em->getRepository('ApiBundle:Offer')->findOneBy(['id' => $post['id']]);
                if (!$offer or $offer->getUser()->getId() !== $this->userService->userEntity->getId()) {
                    throw OfferException::invalidOffer();
                }
            } else {
                $offer = new \ApiBundle\Entity\Offer();
            }

            $offer->setName($post['name']);

            $offer->setPrice($post['price']);
            $offer->setAfterAccident($post['afterAccident'] ?? false);
            $offer->setUsed($post['used'] ?? false);
            $offer->setDoors($post['doors']);
            $offer->setFuelType($post['fuelType']);
            $offer->setMeterStatus($post['meterStatus']);
            $offer->setEnginePower($post['enginePower']);
            $offer->setEngineCapacity($post['engineCapacity']);
            $offer->setGearbox($post['gearbox']);
            $offer->setProductionYear($post['productionYear']);
            $offer->setBodyColor($post['bodyColor']);
            $offer->setBodyType($post['bodyType']);
            $offer->setDescription($post['description'] ?? '');
            $offer->setUser($this->userService->userEntity);

            $brand = $this->em->getRepository('ApiBundle:Brand')->findOneBy(['slug' => $post['brand']]);

            if (!$brand) {
                $brand = $this->em->getRepository('ApiBundle:Brand')->findOneBy(['slug' => TextToolService::stripForSeo($post['brand'])]);
                if (!$brand) {
                    $brand = new \ApiBundle\Entity\Brand();
                    $brand->setName($post['brand']);
                    $brand->setSlug(TextToolService::stripForSeo($post['brand']));
                    $this->em->persist($brand);
                }
            }
            
            $model = $this->em->getRepository('ApiBundle:Model')->findOneBy(['slug' => $post['model'], 'brand' => $brand]);

            if (!$model) {
                $model = $this->em->getRepository('ApiBundle:Model')->findOneBy(['slug' => TextToolService::stripForSeo($post['model']), 'brand' => $brand]);
                if (!$model) {
                    $model = new \ApiBundle\Entity\Model();
                    $model->setName($post['model']);
                    $model->setSlug(TextToolService::stripForSeo($post['model']));
                    $model->setBrand($brand);
                    $this->em->persist($model);
                }
            }
            
            $version = $this->em->getRepository('ApiBundle:Version')->findOneBy(['slug' => $post['version'], 'model' => $model]);

            if (!$version) {
                $version = $this->em->getRepository('ApiBundle:Version')->findOneBy(['slug' => TextToolService::stripForSeo($post['version']), 'model' => $model]);
                if (!$version) {
                    $version = new \ApiBundle\Entity\Version();
                    $version->setName($post['version']);
                    $version->setSlug(TextToolService::stripForSeo($post['version']));
                    $version->setModel($model);
                    $this->em->persist($version);
                }
            }

            if (isset($post['id'])) {
                foreach ($offer->getEqipments() as $equipment) {
                    $offer->removeEqipment($equipment);
                }
            }

            $offer->setVersion($version);
            foreach ($post['equipments'] as $equipmentId => $val) {

                if ($val === true) {
                    $equipment = $this->em->getRepository('ApiBundle:Equipment')->findOneBy(['id' => $equipmentId]);
                    if (!$equipment) {
                        throw OfferException::invalidEquipment();
                    }
                    $offer->addEqipment($equipment);
                }
            }

            $this->em->persist($offer);
            $this->em->flush();
            $this->em->commit();
            return $offer;
        } catch (\Exception $exception) {
            $this->em->rollback();
            throw $exception;
        }
    }

    public function getEditData($id)
    {
        if(!$this->userService->isLogged)
        {
            throw  UserException::userIsNotLogged();
        }

        /**
         * @var $offer \ApiBundle\Entity\Offer
         */
        $offer = $this->em->getRepository('ApiBundle:Offer')->findOneBy(['id' => $id]);

        if (!$offer or $offer->getUser() !== $this->userService->userEntity) {
            throw OfferException::invalidOffer();
        }

        $data = [];
        $data['name'] = $offer->getName();
        $data['brand'] = $offer->getVersion()->getModel()->getBrand()->getSlug();
        $data['model'] = $offer->getVersion()->getModel()->getSlug();
        $data['version'] = $offer->getVersion()->getSlug();
        $data['price'] = $offer->getPrice();
        $data['afterAccident'] = $offer->getAfterAccident();
        $data['used'] = $offer->getUsed();
        $data['doors'] = $offer->getDoors();
        $data['fuelType'] = $offer->getFuelType();
        $data['meterStatus'] = $offer->getMeterStatus();
        $data['enginePower'] = $offer->getEnginePower();
        $data['engineCapacity'] = $offer->getEngineCapacity();
        $data['gearbox'] = $offer->getGearbox();
        $data['productionYear'] = $offer->getProductionYear();
        $data['bodyColor'] = $offer->getBodyColor();
        $data['bodyType'] = $offer->getBodyType();
        $data['description'] = $offer->getDescription();
        $data['equipments'] = [];
        /**
         * @var $eqipment Equipment
         */
        foreach ($offer->getEqipments() as $eqipment) {
            $data['equipments'][$eqipment->getId()] = true;
        }

        return $data;
    }
    public final function  getOfferListByUserId()
    {
        if(!$this->userService->isLogged) {
            throw  UserException::userIsNotLogged();
        }
            /**
             * @var $offers \ApiBundle\Entity\Offer[]
             */
                $offers = $this->em->getRepository('ApiBundle:Offer')->findBy(['user' => $this->userService->userEntity]);

                $resultArray = [];

                foreach ($offers as $offer) {
                     $resultArray[] = [
                    'id' => $resultArray->getId(),
                    'name' => $resultArray->getName(),
                    'photo' => $resultArray->getPhotos()
                    ];
                }
                return $resultArray;



    }
}
