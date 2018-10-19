<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package ApiBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="versions")
 */
class Version
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128, unique=true)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=128, unique=true)
     * @var string
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Model", inversedBy="versions")
     * @ORM\JoinColumn(name="model_id", referencedColumnName="id")
     */
    private $model;
}
