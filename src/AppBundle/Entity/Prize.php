<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prize
 */
class Prize
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $prizeType;

    /**
     * @var string
     */
    private $typeExchange;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $redemptionPartner;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var string
     */
    private $imagePath;

    /**
     * @var string
     */
    private $displayName;

    /**
     * @var float
     */
    private $value;

    /**
     * @var string
     */
    private $smsResponse;

    /**
     * @var string
     */
    private $prizeKeyword;

    /**
     * @var boolean
     */
    private $isActive;

    /**
     * @var integer
     */
    private $quantity;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set prizeType
     *
     * @param string $prizeType
     * @return Prize
     */
    public function setPrizeType($prizeType)
    {
        $this->prizeType = $prizeType;

        return $this;
    }

    /**
     * Get prizeType
     *
     * @return string 
     */
    public function getPrizeType()
    {
        return $this->prizeType;
    }

    /**
     * Set typeExchange
     *
     * @param string $typeExchange
     * @return Prize
     */
    public function setTypeExchange($typeExchange)
    {
        $this->typeExchange = $typeExchange;

        return $this;
    }

    /**
     * Get typeExchange
     *
     * @return string 
     */
    public function getTypeExchange()
    {
        return $this->typeExchange;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Prize
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
     * Set redemptionPartner
     *
     * @param string $redemptionPartner
     * @return Prize
     */
    public function setRedemptionPartner($redemptionPartner)
    {
        $this->redemptionPartner = $redemptionPartner;

        return $this;
    }

    /**
     * Get redemptionPartner
     *
     * @return string 
     */
    public function getRedemptionPartner()
    {
        return $this->redemptionPartner;
    }

    /**
     * Set amount
     *
     * @param float $amount
     * @return Prize
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set imagePath
     *
     * @param string $imagePath
     * @return Prize
     */
    public function setImagePath($imagePath)
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    /**
     * Get imagePath
     *
     * @return string 
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }

    /**
     * Set displayName
     *
     * @param string $displayName
     * @return Prize
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * Get displayName
     *
     * @return string 
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set value
     *
     * @param float $value
     * @return Prize
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return float 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set smsResponse
     *
     * @param string $smsResponse
     * @return Prize
     */
    public function setSmsResponse($smsResponse)
    {
        $this->smsResponse = $smsResponse;

        return $this;
    }

    /**
     * Get smsResponse
     *
     * @return string 
     */
    public function getSmsResponse()
    {
        return $this->smsResponse;
    }

    /**
     * Set prizeKeyword
     *
     * @param string $prizeKeyword
     * @return Prize
     */
    public function setPrizeKeyword($prizeKeyword)
    {
        $this->prizeKeyword = $prizeKeyword;

        return $this;
    }

    /**
     * Get prizeKeyword
     *
     * @return string 
     */
    public function getPrizeKeyword()
    {
        return $this->prizeKeyword;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Prize
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return Prize
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Prize
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
     * @return Prize
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
     * @var integer
     */
    private $productId;


    /**
     * Set productId
     *
     * @param integer $productId
     * @return Prize
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return integer 
     */
    public function getProductId()
    {
        return $this->productId;
    }
    /**
     * @var string
     */
    private $instructions;


    /**
     * Set instructions
     *
     * @param string $instructions
     * @return Prize
     */
    public function setInstructions($instructions)
    {
        $this->instructions = $instructions;

        return $this;
    }

    /**
     * Get instructions
     *
     * @return string 
     */
    public function getInstructions()
    {
        return $this->instructions;
    }
    /**
     * @var integer
     */
    private $order;


    /**
     * Set order
     *
     * @param integer $order
     * @return Prize
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer 
     */
    public function getOrder()
    {
        return $this->order;
    }
    /**
     * @var integer
     */
    private $editable;


    /**
     * Set editable
     *
     * @param integer $editable
     * @return Prize
     */
    public function setEditable($editable)
    {
        $this->editable = $editable;

        return $this;
    }

    /**
     * Get editable
     *
     * @return integer 
     */
    public function getEditable()
    {
        return $this->editable;
    }
    /**
     * @var integer
     */
    private $createdBy;


    /**
     * Set createdBy
     *
     * @param integer $createdBy
     * @return Prize
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
     * @var integer
     */
    private $orderNumber;


    /**
     * Set orderNumber
     *
     * @param integer $orderNumber
     * @return Prize
     */
    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    /**
     * Get orderNumber
     *
     * @return integer 
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }
    /**
     * @var integer
     */
    private $updatedBy;


    /**
     * Set updatedBy
     *
     * @param integer $updatedBy
     * @return Prize
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
     * @var float
     */
    private $probability;

    /**
     * @var integer
     */
    private $alreadyGiven;

    /**
     * @var string
     */
    private $message;

    /**
     * @var integer
     */
    private $countryId;

    /**
     * @var integer
     */
    private $alreadyTaken;

    /**
     * @var \DateTime
     */
    private $activateDate;


    /**
     * Set probability
     *
     * @param float $probability
     * @return Prize
     */
    public function setProbability($probability)
    {
        $this->probability = $probability;

        return $this;
    }

    /**
     * Get probability
     *
     * @return float 
     */
    public function getProbability()
    {
        return $this->probability;
    }

    /**
     * Set alreadyGiven
     *
     * @param integer $alreadyGiven
     * @return Prize
     */
    public function setAlreadyGiven($alreadyGiven)
    {
        $this->alreadyGiven = $alreadyGiven;

        return $this;
    }

    /**
     * Get alreadyGiven
     *
     * @return integer 
     */
    public function getAlreadyGiven()
    {
        return $this->alreadyGiven;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Prize
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set countryId
     *
     * @param integer $countryId
     * @return Prize
     */
    public function setCountryId($countryId)
    {
        $this->countryId = $countryId;

        return $this;
    }

    /**
     * Get countryId
     *
     * @return integer 
     */
    public function getCountryId()
    {
        return $this->countryId;
    }

    /**
     * Set alreadyTaken
     *
     * @param integer $alreadyTaken
     * @return Prize
     */
    public function setAlreadyTaken($alreadyTaken)
    {
        $this->alreadyTaken = $alreadyTaken;

        return $this;
    }

    /**
     * Get alreadyTaken
     *
     * @return integer 
     */
    public function getAlreadyTaken()
    {
        return $this->alreadyTaken;
    }

    /**
     * Set activateDate
     *
     * @param \DateTime $activateDate
     * @return Prize
     */
    public function setActivateDate($activateDate)
    {
        $this->activateDate = $activateDate;

        return $this;
    }

    /**
     * Get activateDate
     *
     * @return \DateTime 
     */
    public function getActivateDate()
    {
        return $this->activateDate;
    }
}
