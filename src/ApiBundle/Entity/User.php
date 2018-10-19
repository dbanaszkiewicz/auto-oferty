<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package ApiBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     * @var string
     */
    private $salt;

    /**
     * @ORM\Column(type="string", length=128)
     * @var string
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=15)
     * @var string
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=30);
     * @var string
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=45)
     * @var string
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=512)
     * @var string
     */
    private $address;

    /**
     * @ORM\Column(type="float")
     * @var double
     */
    private $longitude;
    /**
     * @ORM\Column(type="float")
     * @var double
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=128)
     * @var string
     */
    private $email;
}
