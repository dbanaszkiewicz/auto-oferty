<?php

namespace ApiBundle\Service;

use ApiBundle\Exception\ApiException;
use Doctrine\ORM\EntityManager;
use ApiBundle\Entity\Session;
use ApiBundle\Exception\UserException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class User
 * @package ApiBundle\Service
 */
class User
{
    public $isLogged = false;
    /**
     * @var $userEntity \ApiBundle\Entity\User
     */
    public $userEntity = null;
    public $expireSessionTime = 3*60; // in minutes

    /**
     * @var EntityManager
     */
    private $em = null;

    /**
     * @var Request
     */
    private $request;
    /**
     * @var \ApiBundle\Service\PHPass
     */
    private $PHPass = null;

    /**
     * User constructor.
     * @param EntityManager $entityManager
     * @param PHPass $PhpassManager
     */
    public function __construct(EntityManager $entityManager, PHPass $PhpassManager)
    {
        $this->em = $entityManager;
        $this->PHPass = $PhpassManager;
        $this->request = Request::createFromGlobals();
        $this->setUserInfo();
    }

    private function setUserInfo()
    {
        if ($this->request->cookies->has("sid")) {
            /**
             * @var $session \ApiBundle\Entity\Session
             */
            $session = $this->em->getRepository("ApiBundle:Session")->findOneBy([
                'sid' => $this->request->cookies->get("sid")
            ]);

            if ($session) {

                if ($session->getEndDate()->getTimestamp() > time()) {
                    if (substr($this->request->server->get("HTTP_USER_AGENT"), 0, 250) === $session->getUserAgent()) {
                        if ($this->request->server->get("REMOTE_ADDR") === $session->getIp()) {
                            $this->userEntity = $session->getUser();
                            $this->isLogged = true;
                        }
                    }
                }
            }
        }
    }

    /**
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getUserInfo()
    {
        if ($this->isLogged) {
            /**
             * @var $session \ApiBundle\Entity\Session
             */
            $session = $this->em->getRepository("ApiBundle:Session")->findOneBy([
                'sid' => $this->request->cookies->get("sid")
            ]);


            if ($session) {
                $session->setEndDate($session->getStartDate()->setTimestamp(time() + 60 * $this->expireSessionTime));
                $this->em->flush();
            }
        }

        if ($this->userEntity) {
            return [
                'isLogged' => $this->isLogged,
                'id' => $this->userEntity->getId(),
                'address' => $this->userEntity->getAddress(),
                'name' => $this->userEntity->getFirstName(),
                'phoneNumber' => $this->userEntity->getPhoneNumber(),
                'email' => $this->userEntity->getEmail(),
            ];
        } else {
            return [
                'isLogged' => false,
            ];
        }
    }

    /**
     * @param $email
     * @param $password
     * @return array|string
     * @throws ApiException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function login($email, $password)
    {
        /**
         * @var $user \ApiBundle\Entity\User
         */
        $user = $this->em->getRepository("ApiBundle:User")->findOneBy(['email' => $email]);

        if (!$user) {
            throw UserException::invalidLoginData();
        }

        $password = $this->mixPass($password, $user->getSalt());

        if ($this->PHPass->CheckPassword($password, $user->getPassword())) {
            $this->request->cookies->remove("sid");

            $sid = $this->generateSid();

            $this->em->getRepository("ApiBundle:Session");

            $session = new Session();
            $session->setStartDate((new \DateTime())->setTimestamp(time()));
            $session->setEndDate($session->getStartDate()->setTimestamp(time() + 60 * $this->expireSessionTime));
            $session->setIp($this->request->server->get("REMOTE_ADDR"));
            $session->setUserAgent(substr($this->request->server->get("HTTP_USER_AGENT"), 0, 250));
            $session->setUser($user);
            $session->setSid($sid);

            $this->em->persist($session);
            $this->em->flush();

            return $sid;
        } else {
            throw UserException::invalidLoginData();
        }
    }

    /**
     * @param $email
     * @param $password
     * @param $firstName
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function register($email, $password, $firstName)
    {
        $user = $this->em->getRepository("ApiBundle:User")->findOneBy(['email' => $email]);
        if ($user) {
            throw UserException::userWithEmailExist();
        }

        if (strlen($password) < 5) {
            throw UserException::invalidPassword();
        }

        if (strlen($firstName) < 3) {
            throw UserException::invalidFirstName();
        }

        $user = new \ApiBundle\Entity\User();
        $user->setEmail($email);
        $user->setFirstName($firstName);
        $salt = $this->generateSalt();
        $user->setSalt($salt);
        $password = $this->PHPass->HashPassword($this->mixPass($password, $salt));
        $user->setPassword($password);
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function logout()
    {
        if ($this->isLogged) {
            $sid = $this->request->cookies->get("sid", null);

            /**
             * @var $session \ApiBundle\Entity\Session
             */
            $session = $this->em->getRepository("ApiBundle:Session")->findOneBy(['sid' => $sid]);

            if ($session) {
                $session->setEndDate($session->getEndDate()->setTimestamp(time()-1));
                $this->em->flush();
            }
        }
    }

    /**
     * @return string
     */
    private function generateSalt()
    {
        $keys = "12344567890qwertyuiopasdfghjklzxcvbnm!@#$%^&*()QWERTYUIOPASDFGHJKLZXCVBNM";

        $salt = array();
        for ($i = 0; $i < 55; $i++) {
            $salt[] = $keys[rand(0, strlen($keys) - 1)];
        }

        $salt = implode("", $salt);

        if ($this->em->getRepository("ApiBundle:User")->findBy(['salt' => $salt])) {
            return $this->generateSalt();
        }
        return (string) $salt;
    }

    /**
     * @return string
     */
    private function generateSid()
    {
        $keys = "12344567890qwertyuiopasdfghjklzxcvbnm!@#$%^&*()QWERTYUIOPASDFGHJKLZXCVBNM";

        $sid = array();
        for ($i = 0; $i < 50; $i++) {
            $sid[] = $keys[rand(0, strlen($keys) - 1)];
        }

        $sid = implode("", $sid);

        if ($this->em->getRepository("ApiBundle:Session")->findBy(['sid' => $sid])) {
            return $this->generateSid();
        }
        return (string) $sid;
    }

    /**
     * Metoda mieszająca hasło z solą
     * @param $pass
     * @param $salt
     * @return string
     */
    private function mixPass($pass, $salt)
    {
        $result = array();

        $result[0] = $salt[0];
        $result[1] = $salt[1];
        $result[2] = $salt[2];

        for ($i = 0; $i < max(strlen($pass), strlen($salt) - 3); $i++) {
            if (isset($pass[-($i + 1)])) {
                $result[] = $pass[-($i + 1)];
            }
            if (isset($salt[$i + 3])) {
                $result[] = $salt[$i + 3];
            }
        }
        $result = implode("", $result);

        return (string) $result;
    }


    /**
     * @param $newpass
     * @param $oldpass
     * @throws ApiException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function changePass($newpass, $oldpass)
    {
        if (!$this->isLogged) {
            throw UserException::userIsNotLogged();
        }

        $password = $this->mixPass($oldpass, $this->userEntity->getSalt());

        if (!$this->PHPass->CheckPassword($password, $this->userEntity->getPassword())) {
            throw UserException::invalidPassword();
        }

        if (strlen($newpass) < 5) {
            throw UserException::invalidPassword();
        }

        $salt = $this->generateSalt();
        $this->userEntity->setSalt($salt);
        $password = $this->PHPass->HashPassword($this->mixPass($newpass, $salt));
        $this->userEntity->setPassword($password);
        $this->em->persist($this->userEntity);
        $this->em->flush();
    }

    public final function editUser($post)
    {
        $this->userEntity->setAddress($post["adress"]);
        $this->userEntity->setEmail($post["email"]);
        $this->userEntity->setFirstName($post["firstName"]);
        $this->userEntity->setLatitude($post["latitude"]);
        $this->userEntity->setLongitude($post["longitude"]);
        $this->userEntity->setPhoneNumber($post["phoneNumber"]);

        $this->em->flush();

    }
}
