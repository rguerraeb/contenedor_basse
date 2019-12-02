<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StaffCode
 */
class StaffCode
{
    /**
     * @var integer
     */
    private $staffCodeId;

    /**
     * @var string
     */
    private $code;

    /**
     * @var \AppBundle\Entity\Campaign
     */
    private $campaign;

    /**
     * @var \AppBundle\Entity\Staff
     */
    private $staff;

    /**
     * @var \AppBundle\Entity\CodeStatus
     */
    private $codeStatus;

     /**
     *
     * @var string
     */
    private $responsable;
    
    /**
     *
     * @var string
     */
    private $store;
    
    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * Get staffCodeId
     *
     * @return integer 
     */
    public function getStaffCodeId()
    {
        return $this->staffCodeId;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return staffCode
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set campaign
     *
     * @param \AppBundle\Entity\Campaign $campaign
     * @return StaffCode
     */
    public function setCampaign(\AppBundle\Entity\Campaign $campaign = null)
    {
        $this->campaign = $campaign;

        return $this;
    }

    /**
     * Get campaign
     *
     * @return \AppBundle\Entity\Campaign 
     */
    public function getCampaign()
    {
        return $this->campaign;
    }

    /**
     * Set staff
     *
     * @param \AppBundle\Entity\Staff $staff
     * @return StaffCode
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
     * Set codeStatus
     *
     * @param \AppBundle\Entity\CodeStatus $codeStatus
     * @return StaffCode
     */
    public function setCodeStatus(\AppBundle\Entity\CodeStatus $codeStatus = null)
    {
        $this->codeStatus = $codeStatus;

        return $this;
    }

    /**
     * Get codeStatus
     *
     * @return \AppBundle\Entity\CodeStatus 
     */
    public function getCodeStatus()
    {
        return $this->codeStatus;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return StaffCode
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return StaffCode
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

    public function __toString() {
    	return $this->getCode();
    }

      /**
     * Set responsable
     *
     * @param string $responsable
     * @return StaffPromotion
     */
    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable
     *
     * @return string 
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * Set store
     *
     * @param string $store
     * @return StaffPromotion
     */
    public function setStore($store)
    {
        $this->store = $store;

        return $this;
    }

    /**
     * Get store
     *
     * @return string 
     */
    public function getStore()
    {
        return $this->store;
    }
    /**
     * @var string
     */
    private $billNumber;


    /**
     * Set billNumber
     *
     * @param string $billNumber
     * @return StaffCode
     */
    public function setBillNumber($billNumber)
    {
        $this->billNumber = $billNumber;

        return $this;
    }

    /**
     * Get billNumber
     *
     * @return string 
     */
    public function getBillNumber()
    {
        return $this->billNumber;
    }
    /**
     * @var \AppBundle\Entity\Prize
     */
    private $prize;


    /**
     * Set prize
     *
     * @param \AppBundle\Entity\Prize $prize
     * @return StaffCode
     */
    public function setPrize(\AppBundle\Entity\Prize $prize = null)
    {
        $this->prize = $prize;

        return $this;
    }

    /**
     * Get prize
     *
     * @return \AppBundle\Entity\Prize 
     */
    public function getPrize()
    {
        return $this->prize;
    }
}
