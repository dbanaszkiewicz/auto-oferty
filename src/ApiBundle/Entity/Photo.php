<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package ApiBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="photos")
 */
class Photo
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $order;

    /**
     * @ORM\Column(type="string", length=256, unique=true)
     * @var string
     */
    private $filename;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Offer", inversedBy="photos")
     * @ORM\JoinColumn(name="offer_id", referencedColumnName="id")
     */
    private $offer;
}
