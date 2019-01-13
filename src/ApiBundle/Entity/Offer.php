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
     * @ORM\Column(type="string", length=128)
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
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User", inversedBy="offers")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var User
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $createTime;


    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $expireTime;

    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $visitCounter = 0;

    /**
     * @ORM\ManyToMany(targetEntity="ApiBundle\Entity\Equipment")
     * @ORM\JoinTable(name="offer_equipments")
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
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Photo", mappedBy="offer")
     */
    private $photos;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->eqipments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->photos = new \Doctrine\Common\Collections\ArrayCollection();

        $this->createTime = time();
        $this->expireTime = time() + 30* 24*60*60;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Offer
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set price.
     *
     * @param int $price
     *
     * @return Offer
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set afterAccident.
     *
     * @param bool $afterAccident
     *
     * @return Offer
     */
    public function setAfterAccident($afterAccident)
    {
        $this->afterAccident = $afterAccident;

        return $this;
    }

    /**
     * Get afterAccident.
     *
     * @return bool
     */
    public function getAfterAccident()
    {
        return $this->afterAccident;
    }

    /**
     * Set used.
     *
     * @param bool $used
     *
     * @return Offer
     */
    public function setUsed($used)
    {
        $this->used = $used;

        return $this;
    }

    /**
     * Get used.
     *
     * @return bool
     */
    public function getUsed()
    {
        return $this->used;
    }

    /**
     * Set doors.
     *
     * @param string $doors
     *
     * @return Offer
     */
    public function setDoors($doors)
    {
        $this->doors = $doors;

        return $this;
    }

    /**
     * Get doors.
     *
     * @return string
     */
    public function getDoors()
    {
        return $this->doors;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Offer
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set fuelType.
     *
     * @param string $fuelType
     *
     * @return Offer
     */
    public function setFuelType($fuelType)
    {
        $this->fuelType = $fuelType;

        return $this;
    }

    /**
     * Get fuelType.
     *
     * @return string
     */
    public function getFuelType()
    {
        return $this->fuelType;
    }

    /**
     * Set enginePower.
     *
     * @param int $enginePower
     *
     * @return Offer
     */
    public function setEnginePower($enginePower)
    {
        $this->enginePower = $enginePower;

        return $this;
    }

    /**
     * Get enginePower.
     *
     * @return int
     */
    public function getEnginePower()
    {
        return $this->enginePower;
    }

    /**
     * Set engineCapacity.
     *
     * @param int $engineCapacity
     *
     * @return Offer
     */
    public function setEngineCapacity($engineCapacity)
    {
        $this->engineCapacity = $engineCapacity;

        return $this;
    }

    /**
     * Get engineCapacity.
     *
     * @return int
     */
    public function getEngineCapacity()
    {
        return $this->engineCapacity;
    }

    /**
     * Set meterStatus.
     *
     * @param int $meterStatus
     *
     * @return Offer
     */
    public function setMeterStatus($meterStatus)
    {
        $this->meterStatus = $meterStatus;

        return $this;
    }

    /**
     * Get meterStatus.
     *
     * @return int
     */
    public function getMeterStatus()
    {
        return $this->meterStatus;
    }

    /**
     * Set gearbox.
     *
     * @param string $gearbox
     *
     * @return Offer
     */
    public function setGearbox($gearbox)
    {
        $this->gearbox = $gearbox;

        return $this;
    }

    /**
     * Get gearbox.
     *
     * @return string
     */
    public function getGearbox()
    {
        return $this->gearbox;
    }

    /**
     * Set productionYear.
     *
     * @param int $productionYear
     *
     * @return Offer
     */
    public function setProductionYear($productionYear)
    {
        $this->productionYear = $productionYear;

        return $this;
    }

    /**
     * Get productionYear.
     *
     * @return int
     */
    public function getProductionYear()
    {
        return $this->productionYear;
    }

    /**
     * Set bodyColor.
     *
     * @param string $bodyColor
     *
     * @return Offer
     */
    public function setBodyColor($bodyColor)
    {
        $this->bodyColor = $bodyColor;

        return $this;
    }

    /**
     * Get bodyColor.
     *
     * @return string
     */
    public function getBodyColor()
    {
        return $this->bodyColor;
    }

    /**
     * Set bodyType.
     *
     * @param string $bodyType
     *
     * @return Offer
     */
    public function setBodyType($bodyType)
    {
        $this->bodyType = $bodyType;

        return $this;
    }

    /**
     * Get bodyType.
     *
     * @return string
     */
    public function getBodyType()
    {
        return $this->bodyType;
    }

    /**
     * Set version.
     *
     * @param \ApiBundle\Entity\Version $version
     *
     * @return Offer
     */
    public function setVersion(\ApiBundle\Entity\Version $version = null)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version.
     *
     * @return \ApiBundle\Entity\Version
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Add eqipment.
     *
     * @param \ApiBundle\Entity\Equipment $eqipment
     *
     * @return Offer
     */
    public function addEqipment(\ApiBundle\Entity\Equipment $eqipment)
    {
        $this->eqipments[] = $eqipment;

        return $this;
    }

    /**
     * Remove eqipment.
     *
     * @param \ApiBundle\Entity\Equipment $eqipment
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeEqipment(\ApiBundle\Entity\Equipment $eqipment)
    {
        return $this->eqipments->removeElement($eqipment);
    }

    /**
     * Get eqipments.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEqipments()
    {
        return $this->eqipments;
    }

    /**
     * Add photo.
     *
     * @param \ApiBundle\Entity\Photo $photo
     *
     * @return Offer
     */
    public function addPhoto(\ApiBundle\Entity\Photo $photo)
    {
        $this->photos[] = $photo;

        return $this;
    }

    /**
     * Remove photo.
     *
     * @param \ApiBundle\Entity\Photo $photo
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePhoto(\ApiBundle\Entity\Photo $photo)
    {
        return $this->photos->removeElement($photo);
    }

    /**
     * Get photos.
     *
     * @return Photo[]
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Set user.
     *
     * @param \ApiBundle\Entity\User $user
     *
     * @return Offer
     */
    public function setUser(\ApiBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \ApiBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return int
     */
    public function getCreateTime(): int
    {
        return $this->createTime;
    }

    /**
     * @param int $createTime
     */
    public function setCreateTime(int $createTime): void
    {
        $this->createTime = $createTime;
    }

    /**
     * @return int
     */
    public function getExpireTime(): int
    {
        return $this->expireTime;
    }

    /**
     * @param int $expireTime
     */
    public function setExpireTime(int $expireTime): void
    {
        $this->expireTime = $expireTime;
    }

    /**
     * @return int
     */
    public function getVisitCounter(): int
    {
        return $this->visitCounter;
    }

    /**
     * @param int $visitCounter
     */
    public function setVisitCounter(int $visitCounter): void
    {
        $this->visitCounter = $visitCounter;
    }


}
