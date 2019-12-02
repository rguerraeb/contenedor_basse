<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserPointOfSale
 */
class UserPointOfSale
{
    /**
     * @var integer
     */
    private $userPointOfSaleId;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var integer
     */
    private $createdBy;

    /**
     * @var integer
     */
    private $isActive;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var integer
     */
    private $updatedBy;

    /**
     * @var \AppBundle\Entity\PointOfSale
     */
    private $pointOfSale;

    /**
     * @var \AppBundle\Entity\User
     */
    private $user;


    /**
     * Get userPointOfSaleId
     *
     * @return integer 
     */
    public function getUserPointOfSaleId()
    {
        return $this->userPointOfSaleId;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return UserPointOfSale
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
     * Set createdBy
     *
     * @param integer $createdBy
     * @return UserPointOfSale
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return integer 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set isActive
     *
     * @param integer $isActive
     * @return UserPointOfSale
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return integer 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return UserPointOfSale
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set updatedBy
     *
     * @param integer $updatedBy
     * @return UserPointOfSale
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return integer 
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Set pointOfSale
     *
     * @param \AppBundle\Entity\PointOfSale $pointOfSale
     * @return UserPointOfSale
     */
    public function setPointOfSale(\AppBundle\Entity\PointOfSale $pointOfSale = null)
    {
        $this->pointOfSale = $pointOfSale;

        return $this;
    }

    /**
     * Get pointOfSale
     *
     * @return \AppBundle\Entity\PointOfSale 
     */
    public function getPointOfSale()
    {
        return $this->pointOfSale;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return UserPointOfSale
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
