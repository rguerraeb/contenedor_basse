<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PrizePin
 */
class PrizePin
{
    /**
     * @var integer
     */
    private $prizePinId;

    /**
     * @var string
     */
    private $code;

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
    private $prizeId;

    /**
     * @var integer
     */
    private $usedBy;

    /**
     * @var \DateTime
     */
    private $usedAt;

    /**
     * @var string
     */
    private $phoneDeliveredTo;


    /**
     * Get prizePinId
     *
     * @return integer 
     */
    public function getPrizePinId()
    {
        return $this->prizePinId;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return PrizePin
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return PrizePin
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
     * @return PrizePin
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
     * Set prizeId
     *
     * @param integer $prizeId
     * @return PrizePin
     */
    public function setPrizeId($prizeId)
    {
        $this->prizeId = $prizeId;

        return $this;
    }

    /**
     * Get prizeId
     *
     * @return integer 
     */
    public function getPrizeId()
    {
        return $this->prizeId;
    }

    /**
     * Set usedBy
     *
     * @param integer $usedBy
     * @return PrizePin
     */
    public function setUsedBy($usedBy)
    {
        $this->usedBy = $usedBy;

        return $this;
    }

    /**
     * Get usedBy
     *
     * @return integer 
     */
    public function getUsedBy()
    {
        return $this->usedBy;
    }

    /**
     * Set usedAt
     *
     * @param \DateTime $usedAt
     * @return PrizePin
     */
    public function setUsedAt($usedAt)
    {
        $this->usedAt = $usedAt;

        return $this;
    }

    /**
     * Get usedAt
     *
     * @return \DateTime 
     */
    public function getUsedAt()
    {
        return $this->usedAt;
    }

    /**
     * Set phoneDeliveredTo
     *
     * @param string $phoneDeliveredTo
     * @return PrizePin
     */
    public function setPhoneDeliveredTo($phoneDeliveredTo)
    {
        $this->phoneDeliveredTo = $phoneDeliveredTo;

        return $this;
    }

    /**
     * Get phoneDeliveredTo
     *
     * @return string 
     */
    public function getPhoneDeliveredTo()
    {
        return $this->phoneDeliveredTo;
    }
}
