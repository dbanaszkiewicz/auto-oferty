<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package ApiBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="offers")
 */
class Offer
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
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Version")
     * @ORM\JoinColumn(name="version_id", referencedColumnName="id")
     * @var Version
     */
    private $version;

    /**
     * @ORM\ManyToMany(targetEntity="ApiBundle\Entity\Equipment" mappedBy="offer_equipment")
     */
    private $eqipments;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $price;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $afterAccident = false;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $used = false;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $doors;

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $fuelType;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $enginePower;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $engineCapacity;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $meterStatus;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $gearbox;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $productionYear;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $bodyColor;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $bodyType;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Photo" mappedBy="offer")
     */
    private $photos;
}
