<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SearchCriteria
 *
 * @ORM\Table(name="search_criteria")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SearchCriteriaRepository")
 */
class SearchCriteria
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
     * @var int
     *
     * @ORM\Column(name="matchMinAge", type="integer", nullable=true)
     */
    private $matchMinAge;

    /**
     * @var int
     *
     * @ORM\Column(name="matchMaxAge", type="integer", nullable=true)
     */
    private $matchMaxAge;

    /**
     * @var string
     *
     * @ORM\Column(name="matchSex", type="string", length=255, nullable=true)
     */
    private $matchSex;

    /**
     * @var array
     *
     * @ORM\Column(name="professions", type="array", nullable=true)
     */
    private $professions;

    /**
     * @var float
     *
     * @ORM\Column(name="matchDistanceMin", type="float", nullable=true)
     */
    private $matchDistanceMin;

    /**
     * @var float
     *
     * @ORM\Column(name="matchDistanceMax", type="float", nullable=true)
     */
    private $matchDistanceMax;

    /**
     * @var array
     *
     * @ORM\Column(name="matchCities", type="array", nullable=true)
     */
    private $matchCities;

    /**
     * @var array
     *
     * @ORM\Column(name="matchCountries", type="array", nullable=true)
     */
    private $matchCountries;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createDate", type="datetime")
     */
    private $createDate;

}

