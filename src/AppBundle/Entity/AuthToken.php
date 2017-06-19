<?php
/**
 * Created by PhpStorm.
 * User: Danick Takam
 * Date: 17/06/2017
 * Time: 23:55
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Entity()
     * @ORM\Table(name="auth_tokens",
     *      uniqueConstraints={@ORM\UniqueConstraint(name="auth_tokens_value_unique", columns={"value"})}
     * )
     */
    class AuthToken
    {
        /**
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue
         */
        protected $id;

        /**
         * @ORM\Column(type="string")
         */
        protected $value;

        /**
         * @ORM\Column(type="datetime")
         * @var \DateTime
         */
        protected $createdAt;

        /**
         * @ORM\ManyToOne(targetEntity="User")
         * @var User
         */
        protected $user;

    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return AuthToken
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return AuthToken
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return AuthToken
     */
    public function setUser(\AppBundle\Entity\User $user = null)
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
}
