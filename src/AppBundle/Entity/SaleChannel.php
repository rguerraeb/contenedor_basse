<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SaleChannel
 */
class SaleChannel
{
    /**
     * @var integer
     */
    private $saleChannelId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $createdAt;

    /**
     * @var string
     */
    private $createdBy;


    /**
     * Get saleChannelId
     *
     * @return integer 
     */
    public function getSaleChannelId()
    {
        return $this->saleChannelId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return SaleChannel
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
     * @param string $createdAt
     * @return SaleChannel
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return string 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set createdBy
     *
     * @param string $createdBy
     * @return SaleChannel
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return string 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Get toString
     *
     * @return string 
     */
    public function __toString()
    {
        return $this->name;
    }
}
