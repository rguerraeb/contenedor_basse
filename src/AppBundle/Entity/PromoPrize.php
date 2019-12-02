<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PromoPrize
 */
class PromoPrize
{
    /**
     * @var integer
     */
    private $promoPrizeId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $points;

    /**
     * @var float
     */
    private $factor;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \AppBundle\Entity\PromoPrizeType
     */
    private $promoPrizeType;

    /**
     * @var \AppBundle\Entity\Promo
     */
    private $promo;

    /**
     * @var \AppBundle\Entity\Prize
     */
    private $prize;

    /**
     * @var float
     */
    private $probability;

    /**
     * @var integer
     */
    private $maxQuantity;

    /**
     * @var string
     */
    private $notificationMessage;

    /**
     * @var \AppBundle\Entity\PromoPrizeStatus
     */
    private $promoPrizeStatus;

    /**
     * Get promoPrizeId
     *
     * @return integer 
     */
    public function getPromoPrizeId()
    {
        return $this->promoPrizeId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return PromoPrize
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
     * Set points
     *
     * @param integer $points
     * @return PromoPrize
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer 
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set factor
     *
     * @param float $factor
     * @return PromoPrize
     */
    public function setFactor($factor)
    {
        $this->factor = $factor;

        return $this;
    }

    /**
     * Get factor
     *
     * @return float 
     */
    public function getFactor()
    {
        return $this->factor;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return PromoPrize
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
     * Set promoPrizeType
     *
     * @param \AppBundle\Entity\PromoPrizeType $promoPrizeType
     * @return PromoPrize
     */
    public function setPromoPrizeType(\AppBundle\Entity\PromoPrizeType $promoPrizeType = null)
    {
        $this->promoPrizeType = $promoPrizeType;

        return $this;
    }

    /**
     * Get promoPrizeType
     *
     * @return \AppBundle\Entity\PromoPrizeType 
     */
    public function getPromoPrizeType()
    {
        return $this->promoPrizeType;
    }

    /**
     * Set promo
     *
     * @param \AppBundle\Entity\Promo $promo
     * @return PromoPrize
     */
    public function setPromo(\AppBundle\Entity\Promo $promo = null)
    {
        $this->promo = $promo;

        return $this;
    }

    /**
     * Get promo
     *
     * @return \AppBundle\Entity\Promo 
     */
    public function getPromo()
    {
        return $this->promo;
    }

    /**
     * Set prize
     *
     * @param \AppBundle\Entity\Prize $prize
     * @return PromoPrize
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

    /**
     * Set probability
     *
     * @param float $probability
     * @return PromoPrize
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
     * Set maxQuantity
     *
     * @param integer $maxQuantity
     * @return PromoPrize
     */
    public function setMaxQuantity($maxQuantity)
    {
        $this->maxQuantity = $maxQuantity;

        return $this;
    }

    /**
     * Get maxQuantity
     *
     * @return integer 
     */
    public function getMaxQuantity()
    {
        return $this->maxQuantity;
    }

    /**
     * Set notificationMessage
     *
     * @param string $notificationMessage
     * @return PromoPrize
     */
    public function setNotificationMessage($notificationMessage)
    {
        $this->notificationMessage = $notificationMessage;

        return $this;
    }

    /**
     * Get notificationMessage
     *
     * @return string 
     */
    public function getNotificationMessage()
    {
        return $this->notificationMessage;
    }

    /**
     * Sets NULL all the columns that is not expected to use
     *
     */
    public function setNulls() {
        $pptId = $this->promoPrizeType->getPromoPrizeTypeId();

        // Depending on type of prize set NULLS
        if ($pptId == 1) {
            $this->factor = NULL;
            $this->name = NULL;
            $this->prize = NULL;
        }
        else if ($pptId == 2) {
            $this->points = NULL;
            $this->name = NULL;
            $this->prize = NULL;
        }
        else if ($pptId == 3) {
            $this->points = NULL;
            $this->factor = NULL;
            $this->prize = NULL;
        }
        else if ($pptId == 4) {
            $this->points = NULL;
            $this->factor = NULL;
            $this->name = NULL;
        }

        if (!$this->promo->usesPrMqNm()) {
            // Only 'Sorteo' promo has probability, maxQuantity, notification message
            $this->probability = NULL;
            $this->maxQuantity = NULL;
            $this->notificationMessage = NULL;
        }
    }

    /**
     * Set promoPrizeStatus
     *
     * @param \AppBundle\Entity\PromoPrizeStatus $promoPrizeStatus
     * @return PromoPrize
     */
    public function setPromoPrizeStatus(\AppBundle\Entity\PromoPrizeStatus $promoPrizeStatus = null)
    {
        $this->promoPrizeStatus = $promoPrizeStatus;

        return $this;
    }

    /**
     * Get promoPrizeStatus
     *
     * @return \AppBundle\Entity\PromoPrizeStatus 
     */
    public function getPromoPrizeStatus()
    {
        return $this->promoPrizeStatus;
    }
}
