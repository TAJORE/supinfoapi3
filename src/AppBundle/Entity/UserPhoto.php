<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserPhoto
 *
 * @ORM\Table(name="user_photo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserPhotoRepository")
 */
class UserPhoto
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
     * @ORM\Column(name="userId", type="string", length=50)
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="name", type="integer", length=50)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="hashname", type="string", length=255)
     */
    private $hashname;

    /**
     * @var float
     *
     * @ORM\Column(name="size", type="float")
     */
    private $size;

    /**
     * @var string
     *
     * @ORM\Column(name="mimeType", type="string", length=25)
     */
    private $mimeType;

    /**
     * @var bool
     *
     * @ORM\Column(name="isValid", type="boolean")
     */
    private $isValid;

    /**
     * @var string
     *
     * @ORM\Column(name="visibility", type="string", length=25)
     */
    private $visibility;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updateDate", type="datetime")
     */
    private $updateDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createDate", type="datetime")
     */
    private $createDate;


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
     * Set userId
     *
     * @param string $userId
     *
     * @return UserPhoto
     */
    public function setuserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return string
     */
    public function getuserId()
    {
        return $this->userId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return UserPhoto
     */
    public function setname($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getname()
    {
        return $this->name;
    }

    /**
     * Set hashname
     *
     * @param string $hashname
     *
     * @return UserPhoto
     */
    public function sethashname($hashname)
    {
        $this->hashname = $hashname;

        return $this;
    }

    /**
     * Get hashname
     *
     * @return string
     */
    public function gethashname()
    {
        return $this->hashname;
    }

    /**
     * Set size
     *
     * @param float $size
     *
     * @return UserPhoto
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return float
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     *
     * @return UserPhoto
     */
    public function setmimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string
     */
    public function getmimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set isValid
     *
     * @param boolean $isValid
     *
     * @return UserPhoto
     */
    public function setisValid($isValid)
    {
        $this->isValid = $isValid;

        return $this;
    }

    /**
     * Get isValid
     *
     * @return bool
     */
    public function getisValid()
    {
        return $this->isValid;
    }

    /**
     * Set visibility
     *
     * @param string $visibility
     *
     * @return UserPhoto
     */
    public function setvisibility($visibility)
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * Get visibility
     *
     * @return string
     */
    public function getvisibility()
    {
        return $this->visibility;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     *
     * @return UserPhoto
     */
    public function setupdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get updateDate
     *
     * @return \DateTime
     */
    public function getupdateDate()
    {
        return $this->updateDate;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return UserPhoto
     */
    public function setcreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime
     */
    public function getcreateDate()
    {
        return $this->createDate;
    }
}
