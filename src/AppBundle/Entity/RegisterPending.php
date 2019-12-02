<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * RegisterPending
 */
class RegisterPending
{
    /**
     * @var integer
     */
    private $registerPendingId;

    /**
     * @var string
     */
    private $name;
    
    /**
     * @var string
     */
    private $firstName;
    
    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $taxIdentifier;

    /**
     * @var string
     */
    private $citizenId;

    /**
     * @var string
     */
    private $gender;

    /**
     * @var \DateTime
     */
    private $birthdate;

    /**
     * @var string
     */
    private $phoneSecondary;

    /**
     * @var string
     */
    private $phoneMain;

    /**
     * @var string
     */
    private $email;

	/**
     * @var string
     */
    private $constructionCategoryOther;

    /**
     * @var string
     */
    private $zone;

    /**
     * @var string
     */
    private $address1;

    /**
     * @var string
     */
    private $jobPositionId;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var string
     */
    private $passwd;

    /**
     * @var integer
     */
    private $createdBy;

    
    /**
     * @var integer
     */
    private $updatedBy;

    /**
     * @var integer
     */
    private $status;

    /**
     * @var string
     */
    private $comments;

    /**
     * @var string
     */
    private $registerType;

    /**
     * @var \AppBundle\Entity\City
     */
    private $city;

    /**
     * @var \AppBundle\Entity\Country
     */
    private $country;

    /**
     * @var \AppBundle\Entity\PointOfSale
     */
    private $pointOfSale;
    
    /**
     * @var \AppBundle\Entity\PointOfSaleType
     */
    private $pointOfSaleType;

		/**
		 * @var string
		 */
		private $deviceUUID;


	private $purchasedProductDetails;


    /**
     * @var \AppBundle\Entity\State
     */
    private $state;
    
    
    private $productDetailQuantity;
    

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getProductDetailQuantity()
    {
        return $this->productDetailQuantity;
    }
    
    public function setProductDetailQuantity($productDetailQuantity)
    {
        $this->productDetailQuantity = $productDetailQuantity;
    }
    
        

	public function __construct()
	{
		$this->purchasedProductDetails = new ArrayCollection();
		//dump($this->purchasedProductDetails );die;
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
     * Set name
     *
     * @param string $name
     * @return RegisterPending
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
     * Set taxIdentifier
     *
     * @param string $taxIdentifier
     * @return RegisterPending
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
     * Set citizenId
     *
     * @param string $citizenId
     * @return RegisterPending
     */
    public function setCitizenId($citizenId)
    {
        $this->citizenId = $citizenId;

        return $this;
    }

    /**
     * Get citizenId
     *
     * @return string 
     */
    public function getCitizenId()
    {
        return $this->citizenId;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return RegisterPending
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     * @return RegisterPending
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime 
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set phoneSecondary
     *
     * @param string $phoneSecondary
     * @return RegisterPending
     */
    public function setPhoneSecondary($phoneSecondary)
    {
        $this->phoneSecondary = $phoneSecondary;

        return $this;
    }

    /**
     * Get phoneSecondary
     *
     * @return string 
     */
    public function getPhoneSecondary()
    {
        return $this->phoneSecondary;
    }

    /**
     * Set phoneMain
     *
     * @param string $phoneMain
     * @return RegisterPending
     */
    public function setPhoneMain($phoneMain)
    {
        $this->phoneMain = $phoneMain;

        return $this;
    }

    /**
     * Get phoneMain
     *
     * @return string 
     */
    public function getPhoneMain()
    {
        return $this->phoneMain;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return RegisterPending
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
     * Set zone
     *
     * @param string $zone
     * @return RegisterPending
     */
    public function setZone($zone)
    {
        $this->zone = $zone;

        return $this;
    }

    /**
     * Get zone
     *
     * @return string 
     */
    public function getZone()
    {
        return $this->zone;
    }


    /**
     * Set registerType
     *
     * @param string $registerType
     * @return RegisterPending
     */
    public function setRegisterType($registerType)
    {
        $this->registerType = $registerType;

        return $this;
    }

    /**
     * Get registerType
     *
     * @return string 
     */
    public function getRegisterType()
    {
        return $this->registerType;
    }
	

    /**
     * Set address1
     *
     * @param string $address1
     * @return RegisterPending
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
     * Set jobPositionId
     *
     * @param string $jobPositionId
     * @return RegisterPending
     */
    public function setJobPositionId($jobPositionId)
    {
        $this->jobPositionId = $jobPositionId;

        return $this;
    }

    /**
     * Get jobPositionId
     *
     * @return string 
     */
    public function getJobPositionId()
    {
        return $this->jobPositionId;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return RegisterPending
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return RegisterPending
     */
    public function setUpdatedAt($updatedAt)
    {
    	$this->updatedAt = $updatedAt;
    
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
     * Set passwd
     *
     * @param string $passwd
     * @return RegisterPending
     */
    public function setPasswd($passwd)
    {
        $this->passwd = $passwd;

        return $this;
    }

    /**
     * Get passwd
     *
     * @return string 
     */
    public function getPasswd()
    {
        return $this->passwd;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     * @return RegisterPending
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
     * Set updatedBy
     *
     * @param integer $updatedBy
     * @return RegisterPending
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
     * Set status
     *
     * @param integer $status
     * @return RegisterPending
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
     * Set comments
     *
     * @param string $comments
     * @return RegisterPending
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return string 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set city
     *
     * @param City $city
     * @return RegisterPending
     */
    public function setCity(City $city = null)
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
     * @param Country $country
     * @return RegisterPending
     */
    public function setCountry(Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set pointOfSale
     *
     * @param PointOfSale $pointOfSale
     * @return RegisterPending
     */
    public function setPointOfSale(PointOfSale $pointOfSale = null)
    {
        $this->pointOfSale = $pointOfSale;

        return $this;
    }

    /**
     * Get pointOfSale
     *
     * @return PointOfSale
     */
    public function getPointOfSale()
    {
        return $this->pointOfSale;
    }

    /**
     * Set state
     *
     * @param State $state
     * @return RegisterPending
     */
    public function setState(State $state = null)
    {
        $this->state = $state;

        return $this;
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
     * Get state
     *
     * @return State
     */
    public function getState()
    {
        return $this->state;
    }

    public function statusToString(){
        if ($this->status == 1) {
            return 'Pendiente';
        }
        else if ($this->status == 2) {
            return 'Aceptado';
        }
        else if ($this->status == 3) {
            return 'Rechazado';
        }
        return '';
    }
    /**
     * @var string
     */
    private $businessCity;

    /**
     * @var string
     */
    private $businessState;

    /**
     * @var integer
     */
    private $businessZone;

    /**
     * @var string
     */
    private $businessAddress;


    /**
     * Set businessCity
     *
     * @param string $businessCity
     * @return RegisterPending
     */
    public function setBusinessCity($businessCity)
    {
        $this->businessCity = $businessCity;
    
        return $this;
    }

    /**
     * Get businessCity
     *
     * @return string 
     */
    public function getBusinessCity()
    {
        return $this->businessCity;
    }

    /**
     * Set businessState
     *
     * @param string $businessState
     * @return RegisterPending
     */
    public function setBusinessState($businessState)
    {
        $this->businessState = $businessState;
    
        return $this;
    }

    /**
     * Get businessState
     *
     * @return string 
     */
    public function getBusinessState()
    {
        return $this->businessState;
    }

    /**
     * Set businessZone
     *
     * @param integer $businessZone
     * @return RegisterPending
     */
    public function setBusinessZone($businessZone)
    {
        $this->businessZone = $businessZone;
    
        return $this;
    }

    /**
     * Get businessZone
     *
     * @return integer 
     */
    public function getBusinessZone()
    {
        return $this->businessZone;
    }

    /**
     * Set businessAddress
     *
     * @param string $businessAddress
     * @return RegisterPending
     */
    public function setBusinessAddress($businessAddress)
    {
        $this->businessAddress = $businessAddress;
    
        return $this;
    }

    /**
     * Get businessAddress
     *
     * @return string 
     */
    public function getBusinessAddress()
    {
        return $this->businessAddress;
    }
    /**
     * @var string
     */
    private $groupName;

    /**
     * @var string
     */
    private $businessName;

    /**
     * @var integer
     */
    private $businessNit;


    /**
     * Set groupName
     *
     * @param string $groupName
     * @return RegisterPending
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

    /**
     * Set businessName
     *
     * @param string $businessName
     * @return RegisterPending
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
     * Set businessNit
     *
     * @param string $businessNit
     * @return RegisterPending
     */
    public function setBusinessNit($businessNit)
    {
        $this->businessNit = $businessNit;

        return $this;
    }

    /**
     * Get businessNit
     *
     * @return string 
     */
    public function getBusinessNit()
    {
        return $this->businessNit;
    }

    /**
     * @var integer
     */
    private $quantity;

    /**
     * @var string
     */
    private $invoiceNumber;

    /**
     * @var string
     */
    private $invoiceImage;


    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return RegisterPending
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
     * Set invoiceNumber
     *
     * @param string $invoiceNumber
     * @return RegisterPending
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
     * Set invoiceImage
     *
     * @param string $invoiceImage
     * @return RegisterPending
     */
    public function setInvoiceImage($invoiceImage)
    {
        $this->invoiceImage = $invoiceImage;
    
        return $this;
    }

    /**
     * Get invoiceImage
     *
     * @return string 
     */
    public function getInvoiceImage()
    {
        return $this->invoiceImage;
    }
    /**
     * @var integer
     */
    private $staffId;


    /**
     * Set staffId
     *
     * @param integer $staffId
     * @return RegisterPending
     */
    public function setStaffId($staffId)
    {
        $this->staffId = $staffId;
    
        return $this;
    }

    /**
     * Get staffId
     *
     * @return integer 
     */
    public function getStaffId()
    {
        return $this->staffId;
    }
    /**
     * @var string
     */
    private $profession;
    
    /**
     * @var string
     */
    private $otherProfession;

    /**
     * @var string
     */
    private $specialty;

    /**
     * @var boolean
     */
    private $experienceYears;

    /**
     * @var string
     */
    private $cemproClub;

    /**
     * @var string
     */
    private $maritalStatus;

    /**
     * @var boolean
     */
    private $childNum;

    /**
     * @var string
     */
    private $childAge;

    /**
     * @var string
     */
    private $religion;

    /**
     * @var string
     */
    private $phoneThrid;

    /**
     * @var string
     */
    private $social;

    /**
     * @var string
     */
    private $freeTime;


    /**
     * Set profession
     *
     * @param string $profession
     * @return RegisterPending
     */
    public function setProfession($profession)
    {
        $this->profession = $profession;
    
        return $this;
    }
    
    /**
     * Get profession
     *
     * @return string 
     */
    public function getProfession()
    {
        return $this->profession;
    }
    
    /**
     * Set otherProfession
     *
     * @param string $otherProfession
     * @return RegisterPending
     */
    public function setOtherProfession($otherProfession)
    {
        $this->otherProfession = $otherProfession;
    
        return $this;
    }
    
    /**
     * Get otherProfession
     *
     * @return string 
     */
    public function getOtherProfession()
    {
        return $this->otherProfession;
    }

    /**
     * Set specialty
     *
     * @param string $specialty
     * @return RegisterPending
     */
    public function setSpecialty($specialty)
    {
        $this->specialty = $specialty;
    
        return $this;
    }

    /**
     * Get specialty
     *
     * @return string 
     */
    public function getSpecialty()
    {
        return $this->specialty;
    }

    /**
     * Set experienceYears
     *
     * @param boolean $experienceYears
     * @return RegisterPending
     */
    public function setExperienceYears($experienceYears)
    {
        $this->experienceYears = $experienceYears;
    
        return $this;
    }

    /**
     * Get experienceYears
     *
     * @return boolean 
     */
    public function getExperienceYears()
    {
        return $this->experienceYears;
    }

    /**
     * Set cemproClub
     *
     * @param string $cemproClub
     * @return RegisterPending
     */
    public function setCemproClub($cemproClub)
    {
        $this->cemproClub = $cemproClub;
    
        return $this;
    }

    /**
     * Get cemproClub
     *
     * @return string 
     */
    public function getCemproClub()
    {
        return $this->cemproClub;
    }

    /**
     * Set maritalStatus
     *
     * @param string $maritalStatus
     * @return RegisterPending
     */
    public function setMaritalStatus($maritalStatus)
    {
        $this->maritalStatus = $maritalStatus;
    
        return $this;
    }

    /**
     * Get maritalStatus
     *
     * @return string 
     */
    public function getMaritalStatus()
    {
        return $this->maritalStatus;
    }

    /**
     * Set childNum
     *
     * @param boolean $childNum
     * @return RegisterPending
     */
    public function setChildNum($childNum)
    {
        $this->childNum = $childNum;
    
        return $this;
    }

    /**
     * Get childNum
     *
     * @return boolean 
     */
    public function getChildNum()
    {
        return $this->childNum;
    }

    /**
     * Set childAge
     *
     * @param string $childAge
     * @return RegisterPending
     */
    public function setChildAge($childAge)
    {
        $this->childAge = $childAge;
    
        return $this;
    }

    /**
     * Get childAge
     *
     * @return string 
     */
    public function getChildAge()
    {
        return $this->childAge;
    }

    /**
     * Set religion
     *
     * @param string $religion
     * @return RegisterPending
     */
    public function setReligion($religion)
    {
        $this->religion = $religion;
    
        return $this;
    }

    /**
     * Get religion
     *
     * @return string 
     */
    public function getReligion()
    {
        return $this->religion;
    }

    /**
     * Set phoneThrid
     *
     * @param string $phoneThrid
     * @return RegisterPending
     */
    public function setPhoneThrid($phoneThrid)
    {
        $this->phoneThrid = $phoneThrid;
    
        return $this;
    }

    /**
     * Get phoneThrid
     *
     * @return string 
     */
    public function getPhoneThrid()
    {
        return $this->phoneThrid;
    }

    /**
     * Set social
     *
     * @param string $social
     * @return RegisterPending
     */
    public function setSocial($social)
    {
        $this->social = $social;
    
        return $this;
    }

    /**
     * Get social
     *
     * @return string 
     */
    public function getSocial()
    {
        return $this->social;
    }

    /**
     * Set freeTime
     *
     * @param string $freeTime
     * @return RegisterPending
     */
    public function setFreeTime($freeTime)
    {
        $this->freeTime = $freeTime;
    
        return $this;
    }

    /**
     * Get freeTime
     *
     * @return string 
     */
    public function getFreeTime()
    {
        return $this->freeTime;
    }
    /**
     * @var string
     */
    private $cupon;



    /**
     * Set cupon
     *
     * @param string $cupon
     * @return RegisterPending
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

  
    private $profileImage;
    
    /**
     * @return mixed
     */
    public function getProfileImage ()
    {
        return $this->profileImage;
    }
    
    /**
     * @param mixed $profileImage
     */
    public function setProfileImage ($profileImage)
    {
        $this->profileImage = $profileImage;
        return $this;
    }

    /**
     * Set pointOfSaleType
     *
     * @param PointOfSaleType $pointOfSaleType
     * @return RegisterPending
     */
    public function setPointOfSaleType(PointOfSaleType $pointOfSaleType = null)
    {
        $this->pointOfSaleType = $pointOfSaleType;

        return $this;
    }

    /**
     * Get pointOfSaleType
     *
     * @return PointOfSaleType
     */
    public function getPointOfSaleType()
    {
        return $this->pointOfSaleType;
    }
	

    
    /**
     * @var string
     */
    private $deviceUuid;


}
