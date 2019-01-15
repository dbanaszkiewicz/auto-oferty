<?php

namespace ApiBundle\Service;

use ApiBundle\Entity\Equipment;
use ApiBundle\Entity\Photo;
use ApiBundle\Exception\OfferException;
use Doctrine\ORM\EntityManager;
use ApiBundle\Exception\UserException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
        if (!$this->userService->isLogged) {
            throw  UserException::userIsNotLogged();
        }
        
        $this->em->beginTransaction();
        try {
            if ($post['id'] != 0) {
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

            $brand = $this->em->getRepository('ApiBundle:Brand')->findOneBy(['id' => $post['brand']]);

            if (!$brand) {
                $brand = $this->em->getRepository('ApiBundle:Brand')->findOneBy(['slug' => TextToolService::stripForSeo($post['brand'])]);
                if (!$brand) {
                    $brand = new \ApiBundle\Entity\Brand();
                    $brand->setName($post['brand']);
                    $brand->setSlug(TextToolService::stripForSeo($post['brand']));
                    $this->em->persist($brand);
                }
            }
            
            $model = $this->em->getRepository('ApiBundle:Model')->findOneBy(['id' => $post['model'], 'brand' => $brand]);

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
            
            $version = $this->em->getRepository('ApiBundle:Version')->findOneBy(['id' => $post['version'], 'model' => $model]);

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

    /**
     * @param $id
     * @return array
     * @throws OfferException
     * @throws UserException
     */
    public function getEditData($id)
    {
        if (!$this->userService->isLogged) {
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
        $data['brand'] = $offer->getVersion()->getModel()->getBrand()->getId();
        $data['model'] = $offer->getVersion()->getModel()->getId();
        $data['version'] = $offer->getVersion()->getId();
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
        $data['photos'] = [];

        /**
         * @var $photo Photo
         */
        foreach ($offer->getPhotos() as $photo) {
            $data['photos'][] = [
                'id' => $photo->getId(),
                'name' => $photo->getFilename()
            ];
        }

        $data['equipments'] = [];
        /**
         * @var $equipment Equipment
         */
        foreach ($offer->getEqipments() as $equipment) {
            $data['equipments'][$equipment->getId()] = true;
        }

        return $data;
    }

    /**
     * @return array
     * @throws UserException
     */
    public final function getOfferListByUserId()
    {
        if (!$this->userService->isLogged) {
            throw  UserException::userIsNotLogged();
        }
        /**
         * @var $offers \ApiBundle\Entity\Offer[]
         */
        $offers = $this->em->getRepository('ApiBundle:Offer')->findBy(['user' => $this->userService->userEntity]);

        $resultArray = [];

        foreach ($offers as $offer) {
            $item = [
                'id' => $offer->getId(),
                'name' => $offer->getName(),
                "photo" => count($offer->getPhotos()) > 0 ? ($offer->getPhotos()[0])->getFilename() : "default.png",
                'createDate' => date('d.m.Y H:i', $offer->getCreateTime()),
                'expireDate' => date('d.m.Y H:i', $offer->getExpireTime()),
                'renewable' => ($offer->getExpireTime() -  time() - 7*24*60*60) < 0,
                'viewCounter' => $offer->getVisitCounter()
            ];

            $resultArray[] = $item;
        }
        return $resultArray;
    }

    /**
     * @param $id
     * @param $photos
     * @return int
     * @throws OfferException
     * @throws UserException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public final function addPhoto($id, $photos) {
        /**
         * @var $photo UploadedFile
         */
        $photo = $photos['file'];

        if (!$this->userService->isLogged) {
            throw  UserException::userIsNotLogged();
        }

        /**
         * @var $offer \ApiBundle\Entity\Offer
         */
        $offer = $this->em->getRepository('ApiBundle:Offer')->findOneBy(['id' => $id]);

        if (!$offer or $offer->getUser() !== $this->userService->userEntity) {
            throw OfferException::invalidOffer();
        }

        $filename = $id . '_' . microtime() . uniqid();
        $filename = str_replace(' ', '',  $filename);
        $filename = str_replace('.', '',  $filename);

        $filename = $filename . '.' . strtolower($photo->getClientOriginalExtension());
        if (move_uploaded_file($photo->getPathname(), __DIR__ . '/../../../web/storage/' . $filename)) {
            $photo = new Photo();
            $photo->setFilename($filename);
            $photo->setOffer($offer);
            $photo->setOrder(count($offer->getPhotos()));
            $this->em->persist($photo);
            $this->em->flush();

            return $photo->getId();
        }

        throw OfferException::unexpectedUploadError();
    }

    /**
     * @param $post
     * @return array
     */
    public final function find($post) {
        /**
         * @var $offers \ApiBundle\Entity\Offer[]
         */
        $offers = $this->em->getRepository("ApiBundle:Offer")->findAll();

        $return = [];
        foreach ($offers as $offer) {

            if ($offer->getExpireTime() < time()) {
                continue;
            }

            if (isset($post['name']) && strlen($post['name']) > 0) {
                $name = false;
                foreach (explode(' ', $post['name']) as $n) {
                    if (stripos($offer->getName(), $n)) {
                        $name = true;
                    }
                }

                if (!$name) {
                    continue;
                }
            }

            if (isset($post['brand']) && $post['brand'] > 0) {
                if ($offer->getVersion()->getModel()->getBrand()->getId() != $post['brand']) {
                    continue;
                }
            }

            if (isset($post['model']) && $post['model'] > 0) {
                if ($offer->getVersion()->getModel()->getId() != $post['model']) {
                    continue;
                }
            }

            if (isset($post['version']) && $post['version'] > 0) {
                if ($offer->getVersion()->getId() != $post['version']) {
                    continue;
                }
            }

            $arr = [
                [
                    'min' => $post['priceFrom'] ?? 0,
                    'max' => $post['priceTo'] ?? 0,
                    'val' => $offer->getPrice()
                ],
                [
                    'min' => $post['meterStatusFrom'] ?? 0,
                    'max' => $post['meterStatusTo'] ?? 0,
                    'val' => $offer->getMeterStatus()
                ],
                [
                    'min' => $post['enginePowerFrom'] ?? 0,
                    'max' => $post['enginePowerTo'] ?? 0,
                    'val' => $offer->getEnginePower()
                ],
                [
                    'min' => $post['productionYearFrom'] ?? 0,
                    'max' => $post['productionYearTo'] ?? 0,
                    'val' => $offer->getProductionYear()
                ],

            ];
            $t = true;
            foreach ($arr as $a) {
                if ($a['min'] > 0) {
                    if ($a['val'] < $a['min']) {
                        $t = false;
                        break;
                    }
                }
                if ($a['max'] > 0) {
                    if ($a['val'] > $a['max']) {
                        $t = false;
                        break;
                    }
                }
            }

            if (!$t) {
                continue;
            }

            $return[] = $this->getShortDataByOffer($offer);
        }

        return $return;
    }

    /**
     * @param $id
     * @throws OfferException
     * @throws UserException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public final function removeOffer($id) {
        if (!$this->userService->isLogged) {
            throw  UserException::userIsNotLogged();
        }

        /**
         * @var $offer \ApiBundle\Entity\Offer
         */
        $offer = $this->em->getRepository('ApiBundle:Offer')->findOneBy(['id' => $id]);

        if (!$offer or $offer->getUser() !== $this->userService->userEntity) {
            throw OfferException::invalidOffer();
        }

        foreach ($offer->getPhotos() as $photo) {
            if (file_exists(__DIR__ . '/../../../web/storage/' . $photo->getFilename())) {
                unlink(__DIR__ . '/../../../web/storage/' . $photo->getFilename());
            }
        }

        $this->em->remove($offer);
        $this->em->flush();
    }

    /**
     * @param $id
     * @throws OfferException
     * @throws UserException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public final function renewOffer($id) {
        if (!$this->userService->isLogged) {
            throw  UserException::userIsNotLogged();
        }

        /**
         * @var $offer \ApiBundle\Entity\Offer
         */
        $offer = $this->em->getRepository('ApiBundle:Offer')->findOneBy(['id' => $id]);

        if (!$offer or $offer->getUser() !== $this->userService->userEntity) {
            throw OfferException::invalidOffer();
        }

        $offer->setExpireTime(time()+30*24*60*60);
        $this->em->flush();
    }

    /**
     * @param $id
     * @throws OfferException
     * @throws UserException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public final function removePhoto($id) {
        if (!$this->userService->isLogged) {
            throw  UserException::userIsNotLogged();
        }

        $photo = $this->em->getRepository('ApiBundle:Photo')->findOneBy(['id' => $id]);

        if ($photo) {
            if ($photo->getOffer()->getUser() !== $this->userService->userEntity) {
                throw OfferException::invalidOffer();
            } else {
                if (file_exists(__DIR__ . '/../../../web/storage/' . $photo->getFilename())) {
                    unlink(__DIR__ . '/../../../web/storage/' . $photo->getFilename());
                }
                $this->em->remove($photo);
                $this->em->flush();
            }

        }
    }


    /**
     * @param $id
     * @return array
     * @throws OfferException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public final function getOffer($id) {
        /**
         * @var $offer \ApiBundle\Entity\Offer
         */
        $offer = $this->em->getRepository('ApiBundle:Offer')->findOneBy(['id' => $id]);

        if (!$offer) {
            throw OfferException::invalidOffer();
        }

        $offer->setVisitCounter($offer->getVisitCounter()+1);

        $data = [];
        $data['name'] = $offer->getName();
        $data['brand'] = $offer->getVersion()->getModel()->getBrand()->getName();
        $data['model'] = $offer->getVersion()->getModel()->getName();
        $data['version'] = $offer->getVersion()->getName();
        $data['brandId'] = $offer->getVersion()->getModel()->getBrand()->getId();
        $data['modelId'] = $offer->getVersion()->getModel()->getId();
        $data['versionId'] = $offer->getVersion()->getId();
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
        $data['createData'] = date('d.m.Y H:i', $offer->getCreateTime());
        $data['expireData'] = date('d.m.Y H:i', $offer->getExpireTime());
        $data['visitCounter'] = $offer->getVisitCounter();
        $data['equipments'] = [];
        $data['photos'] = [];
        $data['user'] = [
            'id' => $offer->getUser()->getId(),
            'name' => $offer->getUser()->getFirstName(),
            'phone' => "+48 " . number_format($offer->getUser()->getPhoneNumber(), 0, ',', ' '),
            'longitude' => $offer->getUser()->getLongitude(),
            'latitude' => $offer->getUser()->getLatitude(),
            'address' => $offer->getUser()->getAddress()
        ];
        /**
         * @var $equipment Equipment
         */
        foreach ($offer->getEqipments() as $equipment) {
            $data['equipments'][] = $equipment->getName();
        }
        foreach ($offer->getPhotos() as $photo) {
            $data['photos'][] = $photo->getFilename();
        }

        $this->em->flush();
        return $data;
    }

    public final function getMostPopularOffers() {
        /**
         * @var $offers \ApiBundle\Entity\Offer[]
         */
        $offers = $this->em->getRepository("ApiBundle:Offer")->findBy([], ["visitCounter" => 'DESC']);

        $resultList = [];
        foreach ($offers as $offer) {

            if ($offer->getExpireTime() < time()) {
                continue;
            }

            $resultList[] = $this->getShortDataByOffer($offer);

            if (count($resultList) >= 12) {
                break;
            }
        }

        return $resultList;
    }

    public function getShortDataByOffer(\ApiBundle\Entity\Offer $offer) {
        return [
            "id" => $offer->getId(),
            "name" => $offer->getName(),
            "photo" => count($offer->getPhotos()) > 0 ? ($offer->getPhotos()[0])->getFilename() : "default.png",
            "price" => number_format($offer->getPrice(), 0, '.', ' '),
            "fuelType" => $offer->getFuelType(),
            "enginePower" => $offer->getEnginePower(),
            "productionYear" => $offer->getProductionYear(),
            "meterStatus" => number_format($offer->getMeterStatus(), 0, '.', ' ')
        ];
    }
}

