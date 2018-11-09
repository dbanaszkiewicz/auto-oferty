<?php

namespace ApiBundle\Service;

use ApiBundle\Exception\VersionException;
use Doctrine\ORM\EntityManager;

/**
 * Class Version
 * @package ApiBundle\Service
 */
class Version
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
     * @param $model \ApiBundle\Entity\Model
     * @param $versionName string
     * @return \ApiBundle\Entity\Version
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws VersionException
     */
    public function add(\ApiBundle\Entity\Model $model, $versionName)
    {
        if ($this->findByNameAndModel($versionName, $model)) {
            throw VersionException::versionWithNameExists();
        }

        $version = new \ApiBundle\Entity\Version();
        $version->setName($versionName);
        $version->setSlug(TextToolService::stripForSeo($versionName));
        $version->setModel($model);

        $this->em->persist($version);
        $this->em->flush();

        return $version;
    }

    /**
     * @param $name
     * @return \ApiBundle\Entity\Brand[]|\ApiBundle\Entity\Model[]|\ApiBundle\Entity\Session[]|\ApiBundle\Entity\User[]|\ApiBundle\Entity\Version[]|array|object[]
     */
    public function findByNameAndModel($name, \ApiBundle\Entity\Model $model) {
        return $this->em->getRepository('ApiBundle:Version')->findBy([
            'slug' => TextToolService::stripForSeo($name),
            'model' => $model
        ]);
    }
}
