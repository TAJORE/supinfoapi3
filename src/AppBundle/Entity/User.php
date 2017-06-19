<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Namshi\JOSE\Base64\Base64UrlSafeEncoder;
use Symfony\Component\Security\Core\User\UserInterface;
use FOS\UserBundle\Model\User as BaseUser;
/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements  UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255, nullable=true)
     */
    private $lastName;


    /**
     * @var boolean
     *
     * @ORM\Column(name="isOnline", type="boolean")
     */
    private $isOnline;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthDate", type="date")
     */
    private $birthDate;

    /**
     * @var string
     *
     * @ORM\Column(name="profession", type="string", length=255, nullable=true)
     */
    private $profession;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;


    /**
     * @var string
     *
     * @ORM\Column(name="relationshipStatus", type="string", length=255, nullable=true)
     */
    private $relationshipStatus;


    /**
     * @var string
     *
     * @ORM\Column(name="joinReason", type="string", length=255,nullable=true)
     */
    private $joinReason;

    /**
     * @var string
     *
     * @ORM\Column(name="joinDate", type="datetime", length=30, nullable=true)
     */
    private $joinDate;


    /**
     * @var boolean
     *
     * @ORM\Column(name="isEmailVerified", type="boolean")
     */
    private $isEmailVerified;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isVip", type="boolean")
     */
    private $isVip;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=255)
     */
    private $gender;


    /**
     * @var array
     *
     * @ORM\Column(name="phones", type="array",nullable=true)
     */
    private $phones;


    /**
     * @var array
     *
     * @ORM\Column(name="profileVisibility", type="array",nullable=true)
     */
    private $profileVisibility;


    //fosuser attribute


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set isOnline
     *
     * @param boolean $isOnline
     *
     * @return User
     */
    public function setIsOnline($isOnline)
    {
        $this->isOnline = $isOnline;

        return $this;
    }

    /**
     * Get isOnline
     *
     * @return boolean
     */
    public function getIsOnline()
    {
        return $this->isOnline;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     *
     * @return User
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set profession
     *
     * @param string $profession
     *
     * @return User
     */
    public function setProfession($profession)
    {
        $this->profession = $profession;

        return $this;
    }

    /**
     * Get profession
     *
     * @return string
     */
    public function getProfession()
    {
        return $this->profession;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return User
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set relationshipStatus
     *
     * @param string $relationshipStatus
     *
     * @return User
     */
    public function setRelationshipStatus($relationshipStatus)
    {
        $this->relationshipStatus = $relationshipStatus;

        return $this;
    }

    /**
     * Get relationshipStatus
     *
     * @return string
     */
    public function getRelationshipStatus()
    {
        return $this->relationshipStatus;
    }

    /**
     * Set joinReason
     *
     * @param string $joinReason
     *
     * @return User
     */
    public function setJoinReason($joinReason)
    {
        $this->joinReason = $joinReason;

        return $this;
    }

    /**
     * Get joinReason
     *
     * @return string
     */
    public function getJoinReason()
    {
        return $this->joinReason;
    }

    /**
     * Set isConfirm
     *
     * @param boolean $isEmailVerified
     *
     * @return User
     */
    public function setIsEmailVerified($isEmailVerified)
    {
        $this->isEmailVerified = $isEmailVerified;

        return $this;
    }

    /**
     * Get isEmailVerified
     *
     * @return boolean
     */
    public function getIsEmailVerified()
    {
        return $this->isEmailVerified;
    }

    /**
     * Set isVip
     *
     * @param boolean $isVip
     *
     * @return User
     */
    public function setIsVip($isVip)
    {
        $this->isVip = $isVip;

        return $this;
    }

    /**
     * Get isVip
     *
     * @return boolean
     */
    public function getIsVip()
    {
        return $this->isVip;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set phones
     *
     * @param array $phones
     *
     * @return User
     */
    public function setPhones($phones)
    {
        $this->phones = $phones;

        return $this;
    }

    /**
     * Get phones
     *
     * @return array
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * Set profileVisibility
     *
     * @param array $profileVisibility
     *
     * @return User
     */
    public function setProfileVisibility($profileVisibility)
    {
        $this->profileVisibility = $profileVisibility;

        return $this;
    }

    /**
     * Get profileVisibility
     *
     * @return array
     */
    public function getProfileVisibility()
    {
        return $this->profileVisibility;
    }

    /**
     * @return string
     */
    public function getJoinDate()
    {
        return $this->joinDate;
    }

    /**
     * @param string $joinDate
     */
    public function setJoinDate($joinDate)
    {
        $this->joinDate = $joinDate;
    }

    //save a reel password
    protected $plainPassword;

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }


    public function eraseCredentials()
    {
        // Suppression des données sensibles
        $this->plainPassword = null;
    }




    //fos_user


    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    protected $username;


    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="emailCanonical", type="string", length=255, nullable=true)
     */
    protected $emailCanonical;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    protected $password;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastLogin", type="datetime", nullable=true)
     */
    protected $lastLogin;


    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     */
    protected $roles;




    public function getSalt()
    {
        return null;
    }



    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set emailCanonical
     *
     * @param string $emailCanonical
     *
     * @return User
     */
    public function setEmailCanonical($emailCanonical)
    {
        $this->emailCanonical = $emailCanonical;

        return $this;
    }

    /**
     * Get emailCanonical
     *
     * @return string
     */
    public function getEmailCanonical()
    {
        return $this->emailCanonical;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return User
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set lastLogin
     *
     * @param \DateTime $lastLogin
     *
     * @return User
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get lastLogin
     *
     * @return \DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }



    /**
     * Set roles
     *
     * @param array $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }


}
