<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserProfile
 *
 * @ORM\Table(name="user_profile")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserProfileRepository")
 */
class UserProfile
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="date")
     */
    private $CreationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastUpdateDate", type="date")
     */
    private $lastUpdateDate;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=50)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="company", type="string", length=50)
     */
    private $company;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=50)
     */
    private $country;

    /**
     * @var array
     *
     * @ORM\Column(name="gpsPosition", type="array")
     */
    private $gpsPosition;

    /**
     * @var string
     *
     * @ORM\Column(name="photoProfile", type="string", length=255)
     */
    private $photoProfile;

    /**
     * @var int
     *
     * @ORM\Column(name="userId", type="integer")
     */
    private $userId;


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
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return UserProfile
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getcreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set lastUpdateDate
     *
     * @param \DateTime $lastUpdateDate
     *
     * @return UserProfile
     */
    public function setLastupdateDate($lastUpdateDate)
    {
        $this->lastUpdateDate = $lastUpdateDate;

        return $this;
    }

    /**
     * Get lastUpdateDate
     *
     * @return \DateTime
     */
    public function getlastUpdateDate()
    {
        return $this->lastUpdateDate;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return UserProfile
     */
    public function setcity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getcity()
    {
        return $this->city;
    }

    /**
     * Set company
     *
     * @param string $company
     *
     * @return UserProfile
     */
    public function setcompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string
     */
    public function getcompany()
    {
        return $this->company;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return UserProfile
     */
    public function setcountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set gpsPosition
     *
     * @param array $gpsPosition
     *
     * @return UserProfile
     */
    public function setgpsPosition($gpsPosition)
    {
        $this->gpsPosition = $gpsPosition;

        return $this;
    }

    /**
     * Get gpsPosition
     *
     * @return array
     */
    public function getgpsPosition()
    {
        return $this->gpsPosition;
    }

    /**
     * Set photoProfile
     *
     * @param string $photoProfile
     *
     * @return UserProfile
     */
    public function setphotoProfile($photoProfile)
    {
        $this->photoProfile = $photoProfile;

        return $this;
    }

    /**
     * Get photoProfile
     *
     * @return string
     */
    public function getphotoProfile()
    {
        return $this->photoProfile;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return UserProfile
     */
    public function setuserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getuserId()
    {
        return $this->userId;
    }
}
