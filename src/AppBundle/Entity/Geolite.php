<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Geolite
 *
 * @ORM\Table(name="geolite")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GeoliteRepository")
 */
class Geolite
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
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="postalCode", type="string", length=255)
     */
    private $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=255)
     */
    private $region;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float")
     */
    private $longitude;

    /**
     * @var float
     *
     * @ORM\Column(name="lartitude", type="float")
     */
    private $lartitude;

    /**
     * @var float
     *
     * @ORM\Column(name="population", type="float", nullable=true)
     */
    private $population;

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
     * Set city
     *
     * @param string $city
     *
     * @return Geolite
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Geolite
     */
    public function setCountry($country)
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
     * Set postalCode
     *
     * @param string $postalCode
     *
     * @return Geolite
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set region
     *
     * @param string $region
     *
     * @return Geolite
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return Geolite
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set lartitude
     *
     * @param float $lartitude
     *
     * @return Geolite
     */
    public function setLartitude($lartitude)
    {
        $this->lartitude = $lartitude;

        return $this;
    }

    /**
     * Get lartitude
     *
     * @return float
     */
    public function getLartitude()
    {
        return $this->lartitude;
    }

    /**
     * Set population
     *
     * @param float $population
     *
     * @return Geolite
     */
    public function setPopulation($population)
    {
        $this->population = $population;

        return $this;
    }

    /**
     * Get population
     *
     * @return float
     */
    public function getPopulation()
    {
        return $this->population;
    }
}
