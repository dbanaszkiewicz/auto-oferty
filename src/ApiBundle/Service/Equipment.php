<?php
/**
 * Created by PhpStorm.
 * User: Patryk
 * Date: 07.01.2019
 * Time: 23:10
 */

namespace ApiBundle\Service;

use ApiBundle\ApiBundle;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;

/**
 * class Equipment
 * @packege ApiBundle\Service
 */

class Equipment
{
    /**
     * @var EntityManager
     */

    private $em = null;

    public function  _construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getEquipment()
    {

        $equipmentEntityArray = $this->em->getRepository("ApiBundle:Equipment")->findAll();
        $equipments = [];

        foreach ($equipmentEntityArray as $equipmentEntity) {
            $equipments = [
                'id' => $equipmentEntity->getId(),
                'name' => $equipmentEntity->getName()

            ];
        }
        return $equipments;

    }

}
