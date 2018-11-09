<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * Class User
 * @package ApiBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="models",
 *     uniqueConstraints={
 *        @UniqueConstraint(name="model_unique",
 *            columns={"name", "slug", "brand_id"})
 *    }
 * )
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
     * @ORM\Column(type="string", length=128)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=128)
     * @var string
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Version", mappedBy="model")
     */
    private $versions;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Brand", inversedBy="models")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     */
    private $brand;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->versions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Model
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
     * Set slug.
     *
     * @param string $slug
     *
     * @return Model
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add version.
     *
     * @param \ApiBundle\Entity\Version $version
     *
     * @return Model
     */
    public function addVersion(\ApiBundle\Entity\Version $version)
    {
        $this->versions[] = $version;

        return $this;
    }

    /**
     * Remove version.
     *
     * @param \ApiBundle\Entity\Version $version
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeVersion(\ApiBundle\Entity\Version $version)
    {
        return $this->versions->removeElement($version);
    }

    /**
     * Get versions.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVersions()
    {
        return $this->versions;
    }

    /**
     * Set brand.
     *
     * @param \ApiBundle\Entity\Brand|null $brand
     *
     * @return Model
     */
    public function setBrand(\ApiBundle\Entity\Brand $brand = null)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand.
     *
     * @return \ApiBundle\Entity\Brand|null
     */
    public function getBrand()
    {
        return $this->brand;
    }
}
