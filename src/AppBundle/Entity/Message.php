<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MessageRepository")
 */
class Message
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
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createDate", type="datetimetz")
     */
    private $createDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="isValid", type="boolean")
     */
    private $isValid;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Message", mappedBy="messageParent", cascade={"persist", "remove"})
     */
    private $sousMessages;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Message", inversedBy="sousMessages")
     * @ORM\JoinColumn(name="message_parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $messageParent;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $user;

    /**
     *@ORM\OneToMany(targetEntity="AppBundle\Entity\File", mappedBy="message", cascade={"persist", "remove"})
     *@ORM\JoinColumn(nullable=false)
     */
    private $files;

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
     * Set content
     *
     * @param string $content
     *
     * @return Message
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return Message
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Set isValid
     *
     * @param boolean $isValid
     *
     * @return Message
     */
    public function setIsValid($isValid)
    {
        $this->isValid = $isValid;

        return $this;
    }

    /**
     * Get isValid
     *
     * @return bool
     */
    public function getIsValid()
    {
        return $this->isValid;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sousMessages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add sousMessage
     *
     * @param \AppBundle\Entity\Message $sousMessage
     *
     * @return Message
     */
    public function addSousMessage(\AppBundle\Entity\Message $sousMessage)
    {
        $this->sousMessages[] = $sousMessage;

        return $this;
    }

    /**
     * Remove sousMessage
     *
     * @param \AppBundle\Entity\Message $sousMessage
     */
    public function removeSousMessage(\AppBundle\Entity\Message $sousMessage)
    {
        $this->sousMessages->removeElement($sousMessage);
    }

    /**
     * Get sousMessages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSousMessages()
    {
        return $this->sousMessages;
    }

    /**
     * Set messageParent
     *
     * @param \AppBundle\Entity\Message $messageParent
     *
     * @return Message
     */
    public function setMessageParent(\AppBundle\Entity\Message $messageParent = null)
    {
        $this->messageParent = $messageParent;

        return $this;
    }

    /**
     * Get messageParent
     *
     * @return \AppBundle\Entity\Message
     */
    public function getMessageParent()
    {
        return $this->messageParent;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Message
     */
    public function setUser(\AppBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add file
     *
     * @param \AppBundle\Entity\File $file
     *
     * @return Message
     */
    public function addFile(\AppBundle\Entity\File $file)
    {
        $this->files[] = $file;

        return $this;
    }

    /**
     * Remove file
     *
     * @param \AppBundle\Entity\File $file
     */
    public function removeFile(\AppBundle\Entity\File $file)
    {
        $this->files->removeElement($file);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFiles()
    {
        return $this->files;
    }
}
