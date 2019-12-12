<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InvoiceWhatsapp
 */
class InvoiceWhatsapp
{
    /**
     * @var integer
     */
    private $invoiceId;

    /**
     * @var string
     */
    private $imageName;

    /**
     * @var string
     */
    private $invoiceNumber;

    /**
     * @var string
     */
    private $nit;

    /**
     * @var integer
     */
    private $recurrent;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var integer
     */
    private $productQuantity;

    /**
     * @var string
     */
    private $totalInvoice;

    /**
     * @var boolean
     */
    private $prizeType;

    /**
     * @var \AppBundle\Entity\Staff
     */
    private $staff;

    /**
     * @var \AppBundle\Entity\MainStatus
     */
    private $status;

    /**
     * @var string
     */
    private $rejectionMessage;

    /**
     * Get invoiceId
     *
     * @return integer 
     */
    public function getInvoiceId()
    {
        return $this->invoiceId;
    }

    /**
     * Set imageName
     *
     * @param string $imageName
     * @return InvoiceWhatsapp
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get imageName
     *
     * @return string 
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * Set invoiceNumber
     *
     * @param string $invoiceNumber
     * @return InvoiceWhatsapp
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

    /**
     * Set nit
     *
     * @param string $nit
     * @return InvoiceWhatsapp
     */
    public function setNit($nit)
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Get nit
     *
     * @return string 
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * Set recurrent
     *
     * @param integer $recurrent
     * @return InvoiceWhatsapp
     */
    public function setRecurrent($recurrent)
    {
        $this->recurrent = $recurrent;

        return $this;
    }

    /**
     * Get recurrent
     *
     * @return integer 
     */
    public function getRecurrent()
    {
        return $this->recurrent;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return InvoiceWhatsapp
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
     * Set productQuantity
     *
     * @param integer $productQuantity
     * @return InvoiceWhatsapp
     */
    public function setProductQuantity($productQuantity)
    {
        $this->productQuantity = $productQuantity;

        return $this;
    }

    /**
     * Get productQuantity
     *
     * @return integer 
     */
    public function getProductQuantity()
    {
        return $this->productQuantity;
    }

    /**
     * Set totalInvoice
     *
     * @param string $totalInvoice
     * @return InvoiceWhatsapp
     */
    public function setTotalInvoice($totalInvoice)
    {
        $this->totalInvoice = $totalInvoice;

        return $this;
    }

    /**
     * Get totalInvoice
     *
     * @return string 
     */
    public function getTotalInvoice()
    {
        return $this->totalInvoice;
    }

    /**
     * Set prizeType
     *
     * @param boolean $prizeType
     * @return InvoiceWhatsapp
     */
    public function setPrizeType($prizeType)
    {
        $this->prizeType = $prizeType;

        return $this;
    }

    /**
     * Get prizeType
     *
     * @return boolean 
     */
    public function getPrizeType()
    {
        return $this->prizeType;
    }

    /**
     * Set staff
     *
     * @param \AppBundle\Entity\Staff $staff
     * @return InvoiceWhatsapp
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
     * Set status
     *
     * @param \AppBundle\Entity\MainStatus $status
     * @return InvoiceWhatsapp
     */
    public function setStatus(\AppBundle\Entity\MainStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \AppBundle\Entity\MainStatus 
     */
    public function getStatus()
    {
        return $this->status;
    }


    /**
     * Set rejectionMessage
     *
     * @param string $rejectionMessage
     * @return InvoiceWhatsapp
     */
    public function setRejectionMessage($rejectionMessage)
    {
        $this->rejectionMessage = $rejectionMessage;

        return $this;
    }

    /**
     * Get rejectionMessage
     *
     * @return string 
     */
    public function getRejectionMessage()
    {
        return $this->rejectionMessage;
    }

}
