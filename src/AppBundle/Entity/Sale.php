<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sale
 */
class Sale
{
    /**
     * @var integer
     */
    private $saleId;

    /**
     * @var string
     */
    private $skuCode;

    /**
     * @var string
     */
    private $skuFilterString;

    /**
     * @var string
     */
    private $price;

    /**
     * @var string
     */
    private $clientName;

    /**
     * @var string
     */
    private $clientPhone;

    /**
     * @var \DateTime
     */
    private $issuedAt;

    /**
     * @var string
     */
    private $createdBy;	
	
	/**
     * @var string
     */
    private $constructionCategoryOther;
	

    /**
     * @var \AppBundle\Entity\Sku
     */
    private $sku;

    /**
     * @var \AppBundle\Entity\PointOfSale
     */
    private $pointOfSale;

	 /**
     * @var integer
     */
    private $registerPendingId;
    
    private $filterGroup;

	
    /**
     * Unmapped attribute
     *
     * @var string
     */
    /**
     * @Assert\File(
     *     mimeTypes = {
     *      NULL,
     *      "application/vnd.ms-excel", 
     *      "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
     *      "application/vnd.ms-office",
     *      "application/zip"
     *     },
     *     mimeTypesMessage = "Por favor elija un archivo Excel"
     * )
     */
    private $file;


	/**
     * @return mixed
     */
    public function getFilterGroup ()
    {
        return $this->filterGroup;
    }

    /**
     * @param mixed $filterGroup
     */
    public function setFilterGroup ($filterGroup)
    {
        $this->filterGroup = $filterGroup;
        return $this;
    }

    /**
     * Set registerPendingId
     *
     * @param integer $registerPendingId
     * @return registerPendingId
     */
    public function setRegisterPendingId($registerPendingId)
    {
        $this->registerPendingId = $registerPendingId;

        return $this;
    }

    /**
     * Get registerPendingId
     *
     * @return integer 
     */
    public function getRegisterPendingId()
    {
        return $this->registerPendingId;
    }


    /**
     * Set file
     *
     * @param string $file
     * @return ConectionPin
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile()
    {
        return $this->file;
    }


    /**
     * Get saleId
     *
     * @return integer 
     */
    public function getSaleId()
    {
        return $this->saleId;
    }

    /**
     * Set skuCode
     *
     * @param string $skuCode
     * @return Sale
     */
    public function setSkuCode($skuCode)
    {
        $this->skuCode = $skuCode;

        return $this;
    }

    /**
     * Get skuCode
     *
     * @return string 
     */
    public function getSkuCode()
    {
        return $this->skuCode;
    }

    /**
     * Set skuFilterString
     *
     * @param string $skuFilterString
     * @return Sale
     */
    public function setSkuFilterString($skuFilterString)
    {
        $this->skuFilterString = $skuFilterString;

        return $this;
    }

    /**
     * Get skuFilterString
     *
     * @return string 
     */
    public function getSkuFilterString()
    {
        return $this->skuFilterString;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return Sale
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set clientName
     *
     * @param string $clientName
     * @return Sale
     */
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;

        return $this;
    }

    /**
     * Get clientName
     *
     * @return string 
     */
    public function getClientName()
    {
        return $this->clientName;
    }

    /**
     * Set clientPhone
     *
     * @param string $clientPhone
     * @return Sale
     */
    public function setClientPhone($clientPhone)
    {
        $this->clientPhone = $clientPhone;

        return $this;
    }

    /**
     * Get clientPhone
     *
     * @return string 
     */
    public function getClientPhone()
    {
        return $this->clientPhone;
    }

    /**
     * Set issuedAt
     *
     * @param \DateTime $issuedAt
     * @return Sale
     */
    public function setIssuedAt($issuedAt)
    {
        $this->issuedAt = $issuedAt;

        return $this;
    }

    /**
     * Get issuedAt
     *
     * @return \DateTime 
     */
    public function getIssuedAt()
    {
        return $this->issuedAt;
    }

    /**
     * Set createdBy
     *
     * @param string $createdBy
     * @return Sale
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
     * Set sku
     *
     * @param \AppBundle\Entity\Sku $sku
     * @return Sale
     */
    public function setSku(\AppBundle\Entity\Sku $sku = null)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * Get sku
     *
     * @return \AppBundle\Entity\Sku 
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Set pointOfSale
     *
     * @param \AppBundle\Entity\PointOfSale $pointOfSale
     * @return Sale
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

    public function isValid(){
                
        return $this->invoiceNumber != null && $this->price != null
            && $this->pointOfSale != null ;
    }
    /**
     * @var \AppBundle\Entity\ParserRegister
     */
    private $parserRegister;


    /**
     * Set parserRegister
     *
     * @param \AppBundle\Entity\ParserRegister $parserRegister
     * @return Sale
     */
    public function setParserRegister(\AppBundle\Entity\ParserRegister $parserRegister = null)
    {
        $this->parserRegister = $parserRegister;

        return $this;
    }

    /**
     * Get parserRegister
     *
     * @return \AppBundle\Entity\ParserRegister 
     */
    public function getParserRegister()
    {
        return $this->parserRegister;
    }
    /**
     * @var string
     */
    private $invoiceNumber;


    /**
     * Set invoiceNumber
     *
     * @param string $invoiceNumber
     * @return Sale
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
     * @var boolean
     */
    private $isCancelled;


    /**
     * Set isCancelled
     *
     * @param boolean $isCancelled
     * @return Sale
     */
    public function setIsCancelled($isCancelled)
    {
        $this->isCancelled = $isCancelled;
    
        return $this;
    }

    /**
     * Get isCancelled
     *
     * @return boolean 
     */
    public function getIsCancelled()
    {
        return $this->isCancelled;
    }
    /**
     * @var integer
     */
    private $quantity;


    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return Sale
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    
        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
    /**
     * @var string
     */
    private $cupon;

    /**
     * @var \AppBundle\Entity\constructionCategory
     */
    private $constructionCategory;


    /**
     * Set cupon
     *
     * @param string $cupon
     * @return Sale
     */
    public function setCupon($cupon)
    {
        $this->cupon = $cupon;

        return $this;
    }

    /**
     * Get cupon
     *
     * @return string 
     */
    public function getCupon()
    {
        return $this->cupon;
    }
	
	
	/**
     * Set constructionCategoryOther
     *
     * @param string $constructionCategoryOther
     * @return Sale
     */
    public function setConstructionCategoryOther($constructionCategoryOther)
    {
        $this->constructionCategoryOther = $constructionCategoryOther;

        return $this;
    }

    /**
     * Get constructionCategoryOther
     *
     * @return string 
     */
    public function getConstructionCategoryOther()
    {
        return $this->constructionCategoryOther;
    }
	

    /**
     * Set constructionCategory
     *
     * @param \AppBundle\Entity\ConstructionCategory $constructionCategory
     * @return Sale
     */
    public function setConstructionCategory(\AppBundle\Entity\constructionCategory $constructionCategory = null)
    {
        $this->constructionCategory = $constructionCategory;

        return $this;
    }

    /**
     * Get constructionCategory
     *
     * @return \AppBundle\Entity\ConstructionCategory 
     */
    public function getConstructionCategory()
    {
        return $this->constructionCategory;
    }
    /**
     * @var integer
     */
    private $constructionCategoryId;

    /**
     * @var integer
     */
    private $filterGroupId;

    /**
     * @var integer
     */
    private $horcalsaQuantity;


    /**
     * Set constructionCategoryId
     *
     * @param integer $constructionCategoryId
     * @return Sale
     */
    public function setConstructionCategoryId($constructionCategoryId)
    {
        $this->constructionCategoryId = $constructionCategoryId;

        return $this;
    }

    /**
     * Get constructionCategoryId
     *
     * @return integer 
     */
    public function getConstructionCategoryId()
    {
        return $this->constructionCategoryId;
    }

    /**
     * Set filterGroupId
     *
     * @param integer $filterGroupId
     * @return Sale
     */
    public function setFilterGroupId($filterGroupId)
    {
        $this->filterGroupId = $filterGroupId;

        return $this;
    }

    /**
     * Get filterGroupId
     *
     * @return integer 
     */
    public function getFilterGroupId()
    {
        return $this->filterGroupId;
    }

    /**
     * Set horcalsaQuantity
     *
     * @param integer $horcalsaQuantity
     * @return Sale
     */
    public function setHorcalsaQuantity($horcalsaQuantity)
    {
        $this->horcalsaQuantity = $horcalsaQuantity;

        return $this;
    }

    /**
     * Get horcalsaQuantity
     *
     * @return integer 
     */
    public function getHorcalsaQuantity()
    {
        return $this->horcalsaQuantity;
    }
}
