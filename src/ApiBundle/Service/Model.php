<?php

namespace ApiBundle\Service;

use ApiBundle\Exception\ModelException;
use Doctrine\ORM\EntityManager;

/**
 * Class Model
 * @package ApiBundle\Service
 */
class Model
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
     * @param $brand \ApiBundle\Entity\Brand
     * @param $modelName
     * @return \ApiBundle\Entity\Model
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws ModelException
     */
    public function add(\ApiBundle\Entity\Brand $brand, $modelName)
    {
        if ($this->findByNameAndBrand($modelName, $brand)) {
            throw ModelException::modelWithNameExists();
        }

        $model = new \ApiBundle\Entity\Model();
        $model->setName($modelName);
        $model->setSlug(TextToolService::stripForSeo($modelName));
        $model->setBrand($brand);

        $this->em->persist($model);
        $this->em->flush();

        return $model;
    }

    /**
     * @param $name
     * @return \ApiBundle\Entity\Brand[]|\ApiBundle\Entity\Model[]|\ApiBundle\Entity\Session[]|\ApiBundle\Entity\User[]|\ApiBundle\Entity\Version[]|array|object[]
     */
    public function findByNameAndBrand($name, \ApiBundle\Entity\Brand $brand) {
        return $this->em->getRepository('ApiBundle:Model')->findBy([
            'slug' => TextToolService::stripForSeo($name),
            'brand' => $brand
        ]);
    }
}
