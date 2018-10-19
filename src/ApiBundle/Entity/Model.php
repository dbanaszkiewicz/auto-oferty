<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package ApiBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="models")
 */
class Model
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
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Version" mappedBy="model")
     */
    private $versions;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Brand", inversedBy="models")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     */
    private $brand;
}
