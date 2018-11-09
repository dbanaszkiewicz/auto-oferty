<?php

namespace ApiBundle\Service;

use ApiBundle\Exception\BrandException;
use Doctrine\ORM\EntityManager;

/**
 * Class Brand
 * @package ApiBundle\Service
 */
class Brand
{
    /**
     * @var EntityManager
     */
    private $em = null;

    /**
     * User constructor.
     * @param EntityManager $entityManager
     * @param PHPass $PhpassManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param $brandName
     * @return \ApiBundle\Entity\Brand
     * @throws BrandException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function add($brandName)
    {
        if ($this->findByName($brandName)) {
            throw BrandException::brandWithNameExists();
        }

        $brand = new \ApiBundle\Entity\Brand();
        $brand->setName($brandName);
        $brand->setSlug(TextToolService::stripForSeo($brandName));

        $this->em->persist($brand);
        $this->em->flush();

        return $brand;
    }

    /**
     * @param $name
     * @return \ApiBundle\Entity\Brand[]|\ApiBundle\Entity\Model[]|\ApiBundle\Entity\Session[]|\ApiBundle\Entity\User[]|\ApiBundle\Entity\Version[]|array|object[]
     */
    public function findByName($name) {
        return $this->em->getRepository('ApiBundle:Brand')->findBy(['slug' => TextToolService::stripForSeo($name)]);
    }
}
