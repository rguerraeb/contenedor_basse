<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StaffPointOfSale
 */
class StaffPointOfSale
{
    /**
     * @var integer
     */
    private $idStaffPointOfSale;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var integer
     */
    private $createdBy;

    /**
     * @var \AppBundle\Entity\Staff
     */
    private $staff;

    /**
     * @var \AppBundle\Entity\PointOfSale
     */
    private $pointOfSale;

    /**
     * @var string
     */
    private $status;

    /**
     * @var \DateTime
     */
    private $movedDate;
    
    /**
     * 
     * @var integer
     */
    private $updatedBy;
    
    /**
     * 
     * @var \DateTime
     */
    private $updatedAt;


    /**
     * @return number
     */
    public function getUpdatedBy ()
    {
        return $this->updatedBy;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt ()
    {
        return $this->updatedAt;
    }

    /**
     * @param number $updatedBy
     */
    public function setUpdatedBy ($updatedBy)
    {
        $this->updatedBy = $updatedBy;
        return $this;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt ($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Get idStaffPointOfSale
     *
     * @return integer 
     */
    public function getIdStaffPointOfSale()
    {
        return $this->idStaffPointOfSale;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return StaffPointOfSale
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
     * @return StaffPointOfSale
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
     * Set staff
     *
     * @param \AppBundle\Entity\Staff $staff
     * @return StaffPointOfSale
     */
    public function setStaff(\AppBundle\Entity\Staff $staff = null)
    {
        $this->staff = $staff;

        return $this;
    }

    /**
     * Get staff
     *
     * @return \AppBundle\Entity\Staff 
     */
    public function getStaff()
    {
        return $this->staff;
    }

    /**
     * Set pointOfSale
     *
     * @param \AppBundle\Entity\PointOfSale $pointOfSale
     * @return StaffPointOfSale
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
     * Set status
     *
     * @param string $status
     * @return StaffPointOfSale
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set movedDate
     *
     * @param \DateTime $movedDate
     * @return StaffPointOfSale
     */
    public function setMovedDate($movedDate)
    {
        $this->movedDate = $movedDate;

        return $this;
    }

    /**
     * Get movedDate
     *
     * @return \DateTime 
     */
    public function getMovedDate()
    {
        return $this->movedDate;
    }

    /**
     * Sets as MOVED and adds movedDate
     *
     * @return StaffPointOfSale
     */
    public function setMoved() {
        $this->status = 'MOVED';
        $this->movedDate = new \DateTime();

        return $this;
    }
    
    
    
}
