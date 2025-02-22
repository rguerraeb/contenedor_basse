<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PointOfSale
 */
class PointOfSale
{
    /**
     * @var integer
     */
    private $pointOfSaleId;

    /**
     * @var string
     */
    private $businessName;

    /**
     * @var string
     */
    private $taxIdentifier;

	/**
	 * @var string
	 */
	private $deviceUUID;

    /**
     * @var string
     */
    private $homePhone;

    /**
     * @var string
     */
    private $cellPhone;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $geolocation;

    /**
     * @var string
     */
    private $address1;

    /**
     * @var string
     */
    private $address2;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var string
     */
    private $createdBy;
    
    
    /**
     * @var string
     */
    private $battery;
    
    
    /**
     * @var string
     */
    private $version;
    
     /**
     * @var \DateTime
     */
    private $latestStatus;

    /**
     * @var \AppBundle\Entity\SaleChannel
     */
    private $saleChannel;

    /**
     * @var \AppBundle\Entity\City
     */
    private $city;

    /**
     * @var \AppBundle\Entity\Country
     */
    private $country;

    /**
     * @var \AppBundle\Entity\Region
     */
    private $region;

    /**
     * @var \AppBundle\Entity\State
     */
    private $state;

    /**
     * @var \AppBundle\Entity\PointOfSaleType
     */
    private $pointOfSaleType;

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
     * Get pointOfSaleId
     *
     * @return integer 
     */
    public function getPointOfSaleId()
    {
        return $this->pointOfSaleId;
    }

    /**
     * Set businessName
     *
     * @param string $businessName
     * @return PointOfSale
     */
    public function setBusinessName($businessName)
    {
        $this->businessName = $businessName;

        return $this;
    }

    /**
     * Get businessName
     *
     * @return string 
     */
    public function getBusinessName()
    {
        return $this->businessName;
    }

    /**
     * Set taxIdentifier
     *
     * @param string $taxIdentifier
     * @return PointOfSale
     */
    public function setTaxIdentifier($taxIdentifier)
    {
        $this->taxIdentifier = $taxIdentifier;

        return $this;
    }

    /**
     * Get taxIdentifier
     *
     * @return string 
     */
    public function getTaxIdentifier()
    {
        return $this->taxIdentifier;
    }

	/**
	 * @return string
	 */
	public function getDeviceUUID() {
		return $this->deviceUUID;
	}

	/**
	 * @param string $deviceUUID
	 */
	public function setDeviceUUID( $deviceUUID ) {
		$this->deviceUUID = $deviceUUID;
	}


    /**
     * Set homePhone
     *
     * @param string $homePhone
     * @return PointOfSale
     */
    public function setHomePhone($homePhone)
    {
        $this->homePhone = $homePhone;

        return $this;
    }

    /**
     * Get homePhone
     *
     * @return string 
     */
    public function getHomePhone()
    {
        return $this->homePhone;
    }

    /**
     * Set cellPhone
     *
     * @param string $cellPhone
     * @return PointOfSale
     */
    public function setCellPhone($cellPhone)
    {
        $this->cellPhone = $cellPhone;

        return $this;
    }

    /**
     * Get cellPhone
     *
     * @return string 
     */
    public function getCellPhone()
    {
        return $this->cellPhone;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return PointOfSale
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set geolocation
     *
     * @param string $geolocation
     * @return PointOfSale
     */
    public function setGeolocation($geolocation)
    {
        $this->geolocation = $geolocation;

        return $this;
    }

    /**
     * Get geolocation
     *
     * @return string 
     */
    public function getGeolocation()
    {
        return $this->geolocation;
    }

    /**
     * Set address1
     *
     * @param string $address1
     * @return PointOfSale
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * Get address1
     *
     * @return string 
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * Set address2
     *
     * @param string $address2
     * @return PointOfSale
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get address2
     *
     * @return string 
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return PointOfSale
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
     * @param string $createdBy
     * @return PointOfSale
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
     * Set battery
     *
     * @param string $battery
     * @return PointOfSale
     */
    public function setBattery($battery)
    {
        $this->battery = $battery;

        return $this;
    }

    /**
     * Get battery
     *
     * @return string 
     */
    public function getBattery()
    {
        return $this->battery;
    }
    
    /**
     * Set version
     *
     * @param string $version
     * @return PointOfSale
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return string 
     */
    public function getVersion()
    {
        return $this->version;
    }
    
    /**
     * Set latestStatus
     *
     * @param \DateTime $latestStatus
     * @return PointOfSale
     */
    public function setLatestStatus($latestStatus)
    {
        $this->latestStatus = $latestStatus;

        return $this;
    }

    /**
     * Get latestStatus
     *
     * @return \DateTime 
     */
    public function getLatestStatus()
    {
        return $this->latestStatus;
    }


    /**
     * Set saleChannel
     *
     * @param \AppBundle\Entity\SaleChannel $saleChannel
     * @return PointOfSale
     */
    public function setSaleChannel(\AppBundle\Entity\SaleChannel $saleChannel = null)
    {
        $this->saleChannel = $saleChannel;

        return $this;
    }

    /**
     * Get saleChannel
     *
     * @return \AppBundle\Entity\SaleChannel 
     */
    public function getSaleChannel()
    {
        return $this->saleChannel;
    }

    /**
     * Set city
     *
     * @param \AppBundle\Entity\City $city
     * @return PointOfSale
     */
    public function setCity(\AppBundle\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \AppBundle\Entity\City 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param \AppBundle\Entity\Country $country
     * @return PointOfSale
     */
    public function setCountry(\AppBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \AppBundle\Entity\Country 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set region
     *
     * @param \AppBundle\Entity\Region $region
     * @return PointOfSale
     */
    public function setRegion(\AppBundle\Entity\Region $region = null)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return \AppBundle\Entity\Region 
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set state
     *
     * @param \AppBundle\Entity\State $state
     * @return PointOfSale
     */
    public function setState(\AppBundle\Entity\State $state = null)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return \AppBundle\Entity\State 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set pointOfSaleType
     *
     * @param \AppBundle\Entity\PointOfSaleType $pointOfSaleType
     * @return PointOfSale
     */
    public function setPointOfSaleType(\AppBundle\Entity\PointOfSaleType $pointOfSaleType = null)
    {
        $this->pointOfSaleType = $pointOfSaleType;

        return $this;
    }

    /**
     * Get pointOfSaleType
     *
     * @return \AppBundle\Entity\PointOfSaleType 
     */
    public function getPointOfSaleType()
    {
        return $this->pointOfSaleType;
    }

    /**
     * Get toString
     *
     * @return string 
     */
    public function __toString()
    {
        if ($this->businessName !== NULL) {
            return $this->businessName;
        }
        return "";
    }
    /**
     * @var string
     */
    private $pointOfSaleInnerId;


    /**
     * Set pointOfSaleInnerId
     *
     * @param string $pointOfSaleInnerId
     * @return PointOfSale
     */
    public function setPointOfSaleInnerId($pointOfSaleInnerId)
    {
        $this->pointOfSaleInnerId = $pointOfSaleInnerId;

        return $this;
    }

    /**
     * Get pointOfSaleInnerId
     *
     * @return string 
     */
    public function getPointOfSaleInnerId()
    {
        return $this->pointOfSaleInnerId;
    }
    /**
     * @var \AppBundle\Entity\ParserRegister
     */
    private $parserRegister;


    /**
     * Set parserRegister
     *
     * @param \AppBundle\Entity\ParserRegister $parserRegister
     * @return PointOfSale
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

    public function isValid(){
        return $this->saleChannel != null
            && $this->pointOfSaleInnerId != null && $this->businessName != null;
    }
    /**
     * @var integer
     */
    private $status;


    /**
     * Set status
     *
     * @param integer $status
     * @return PointOfSale
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
     * @var string
     */
    private $groupName;


    /**
     * Set groupName
     *
     * @param string $groupName
     * @return PointOfSale
     */
    public function setGroupName($groupName)
    {
        $this->groupName = $groupName;
    
        return $this;
    }

    /**
     * Get groupName
     *
     * @return string 
     */
    public function getGroupName()
    {
        return $this->groupName;
    }
}
