<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserMessage
 *
 * @ORM\Table(name="user_message")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserMessageRepository")
 */
class UserMessage
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
     * @ORM\Column(name="realDate", type="datetime")
     */
    private $realDate;



    /**
     * @var bool
     *
     * @ORM\Column(name="isLocked", type="boolean")
     */
    private $isLocked;


    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $receiver;



    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Message",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $message;




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
    public function getRealDate()
    {
        return $this->realDate;
    }

    /**
     * @param \DateTime $realDate
     */
    public function setRealDate($realDate)
    {
        $this->realDate = $realDate;
    }

    /**
     * @return boolean
     */
    public function isIsLocked()
    {
        return $this->isLocked;
    }

    /**
     * @param boolean $isLocked
     */
    public function setIsLocked($isLocked)
    {
        $this->isLocked = $isLocked;
    }



    /**
     * @return User
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param User $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }


}

