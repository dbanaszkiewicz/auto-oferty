<?php
/**
 * Created by PhpStorm.
 * User: Patryk
 * Date: 07.01.2019
 * Time: 23:10
 */

namespace ApiBundle\Service;

use Doctrine\ORM\EntityManager;

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

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getEquipment()
    {
        $equipmentEntityArray = $this->em->getRepository("ApiBundle:Equipment")->findAll();
        $equipments = [];

        foreach ($equipmentEntityArray as $equipmentEntity) {
            $equipments[] = [
                'id' => $equipmentEntity->getId(),
                'name' => $equipmentEntity->getName()

            ];
        }
        return $equipments;

    }

}
