<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PromoPrizeType
 */
class PromoPrizeType
{
    /**
     * @var integer
     */
    private $promoPrizeTypeId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \DateTime
     */
    private $createdAt;


    /**
     * Get promoPrizeTypeId
     *
     * @return integer 
     */
    public function getPromoPrizeTypeId()
    {
        return $this->promoPrizeTypeId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return PromoPrizeType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return PromoPrizeType
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
     * Get name
     *
     * @return string 
     */
    public function __toString()
    {
        return $this->name;
    }
}
