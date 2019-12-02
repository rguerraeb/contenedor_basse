<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * StaffPromotion
 */
class StaffPromotion
{
    /**
     * @var integer
     */
    private $staffPromotionId;

    /**
     * @var integer
     */
    private $quantityTaken;

    private $invoiceImg;

    /**
     * Unmapped attribute
     *
     * @var string
     */
    private $invoiceNumber;

    /**
     * Unmapped attribute
     *
     * @var string
     */
    /**
     * Unmapped attribute
     *
     * @var string
     */
    /**
     * @Assert\Image(
     *     maxWidth = 3000,
     *     maxWidthMessage = "La imagen debe tener un ancho menor a 3000 píxeles",
     *     mimeTypesMessage = "Por favor elija una imagen"
     * )
     */
    private $imageFile;

    /**
     * @var \AppBundle\Entity\Staff
     */
    private $staff;

    /**
     * @var \AppBundle\Entity\Promotion
     */
    private $promotion;
    
    /**
     * @var \AppBundle\Entity\Campaign
     */
    private $campaign;
    
    /**
     * @var \AppBundle\Entity\StaffCode
     */
    private $staffCode;
    
    /**
     * @var \AppBundle\Entity\Distributor
     */
    private $distributor;

    /**
     * @var \AppBundle\Entity\User
     */
    private $createdBy;

    /**
     * @var \AppBundle\Entity\User
     */
    private $updatedBy;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * Get staffPromotionId
     *
     * @return integer 
     */
    public function getStaffPromotionId()
    {
        return $this->staffPromotionId;
    }
    
    /**
     * Set quantityTaken
     *
     * @param integer $quantityTaken
     * @return StaffPromotion
     */
    public function setQuantityTaken($quantityTaken)
    {
        $this->quantityTaken = $quantityTaken;

        return $this;
    }

    /**
     * Get quantityTaken
     *
     * @return integer 
     */
    public function getQuantityTaken()
    {
        return $this->quantityTaken;
    }
    
    /**
     * @return mixed
     */
    public function getInvoiceImg ()
    {
        return $this->invoiceImg;
    }
    
    /**
     * @param mixed $invoiceImg
     */
    public function setInvoiceImg ($invoiceImg)
    {
        $this->invoiceImg = $invoiceImg;
        return $this;
    }

    /**
     * Set imageFile
     *
     * @param string $imageFile
     * @return StaffPromotion
     */
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;

        return $this;
    }

    /**
     * Get imageFile
     *
     * @return string 
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set createdBy
     *
     * @param \AppBundle\Entity\User $createdBy
     * @return StaffPromotion
     */
    public function setCreatedBy(\AppBundle\Entity\User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \AppBundle\Entity\User 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set updatedBy
     *
     * @param \AppBundle\Entity\User $updatedBy
     * @return StaffPromotion
     */
    public function setUpdatedBy(\AppBundle\Entity\User $updatedBy = null)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return \AppBundle\Entity\User 
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Set staff
     *
     * @param \AppBundle\Entity\Staff $staff
     * @return StaffPromotion
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
     * Set promotion
     *
     * @param \AppBundle\Entity\Promotion $promotion
     * @return StaffPromotion
     */
    public function setPromotion(\AppBundle\Entity\Promotion $promotion = null)
    {
        $this->promotion = $promotion;

        return $this;
    }

    /**
     * Get promotion
     *
     * @return \AppBundle\Entity\Promotion 
     */
    public function getPromotion()
    {
        return $this->promotion;
    }

    /**
     * Set campaign
     *
     * @param \AppBundle\Entity\Campaign $campaign
     * @return StaffPromotion
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
     * Set staffCode
     *
     * @param \AppBundle\Entity\StaffCode $staffCode
     * @return StaffPromotion
     */
    public function setStaffCode(\AppBundle\Entity\StaffCode $staffCode = null)
    {
        $this->staffCode = $staffCode;

        return $this;
    }

    /**
     * Get staffCode
     *
     * @return \AppBundle\Entity\StaffCode 
     */
    public function getStaffCode()
    {
        return $this->staffCode;
    }

    /**
     * Set distributor
     *
     * @param \AppBundle\Entity\Distributor $distributor
     * @return StaffPromotion
     */
    public function setDistributor(\AppBundle\Entity\Distributor $distributor = null)
    {
        $this->distributor = $distributor;

        return $this;
    }

    /**
     * Get distributor
     *
     * @return \AppBundle\Entity\Distributor 
     */
    public function getDistributor()
    {
        return $this->distributor;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return StaffPromotion
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
     * @return StaffPromotion
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
     * Set invoiceNumber
     *
     * @param string $invoiceNumber
     * @return StaffPromotion
     */
    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    /**
     * Get invoiceNumber
     *
     * @return string 
     */
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

}
