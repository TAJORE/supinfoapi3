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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->CreationDate;
    }

    /**
     * @param \DateTime $CreationDate
     */
    public function setCreationDate($CreationDate)
    {
        $this->CreationDate = $CreationDate;
    }

    /**
     * @return \DateTime
     */
    public function getLastUpdateDate()
    {
        return $this->lastUpdateDate;
    }

    /**
     * @param \DateTime $lastUpdateDate
     */
    public function setLastUpdateDate($lastUpdateDate)
    {
        $this->lastUpdateDate = $lastUpdateDate;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param string $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return array
     */
    public function getGpsPosition()
    {
        return $this->gpsPosition;
    }

    /**
     * @param array $gpsPosition
     */
    public function setGpsPosition($gpsPosition)
    {
        $this->gpsPosition = $gpsPosition;
    }

    /**
     * @return string
     */
    public function getPhotoProfile()
    {
        return $this->photoProfile;
    }

    /**
     * @param string $photoProfile
     */
    public function setPhotoProfile($photoProfile)
    {
        $this->photoProfile = $photoProfile;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

}
