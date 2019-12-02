<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PromoCategory
 */
class PromoCategory
{
    /**
     * @var integer
     */
    private $promoCategoryId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \DateTime
     */
    private $createdAt;


    /**
     * Get promoCategoryId
     *
     * @return integer 
     */
    public function getPromoCategoryId()
    {
        return $this->promoCategoryId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return PromoCategory
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
     * @return PromoCategory
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
