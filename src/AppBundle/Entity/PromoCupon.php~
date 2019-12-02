<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PromoCupon
 */
class PromoCupon
{
    /**
     * @var integer
     */
    private $promoCodeId;

    /**
     * @var string
     */
    private $code;

    /**
     * @var \DateTime
     */
    private $usedAt;

    /**
     * @var integer
     */
    private $saleRewardAmount;

    /**
     * @var float
     */
    private $rewardCriteriaPerBag;

    /**
     * @var float
     */
    private $rewardCriteriaPerBagMixtoListo;

    /**
     * @var \AppBundle\Entity\Sale
     */
    private $sale;

    /**
     * @var \AppBundle\Entity\Staff
     */
    private $staffUsedBy;


    /**
     * Get promoCodeId
     *
     * @return integer 
     */
    public function getPromoCodeId()
    {
        return $this->promoCodeId;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return PromoCupon
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
     * Set usedAt
     *
     * @param \DateTime $usedAt
     * @return PromoCupon
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
     * Set saleRewardAmount
     *
     * @param integer $saleRewardAmount
     * @return PromoCupon
     */
    public function setSaleRewardAmount($saleRewardAmount)
    {
        $this->saleRewardAmount = $saleRewardAmount;

        return $this;
    }

    /**
     * Get saleRewardAmount
     *
     * @return integer 
     */
    public function getSaleRewardAmount()
    {
        return $this->saleRewardAmount;
    }

    /**
     * Set rewardCriteriaPerBag
     *
     * @param float $rewardCriteriaPerBag
     * @return PromoCupon
     */
    public function setRewardCriteriaPerBag($rewardCriteriaPerBag)
    {
        $this->rewardCriteriaPerBag = $rewardCriteriaPerBag;

        return $this;
    }

    /**
     * Get rewardCriteriaPerBag
     *
     * @return float 
     */
    public function getRewardCriteriaPerBag()
    {
        return $this->rewardCriteriaPerBag;
    }

    /**
     * Set rewardCriteriaPerBagMixtoListo
     *
     * @param float $rewardCriteriaPerBagMixtoListo
     * @return PromoCupon
     */
    public function setRewardCriteriaPerBagMixtoListo($rewardCriteriaPerBagMixtoListo)
    {
        $this->rewardCriteriaPerBagMixtoListo = $rewardCriteriaPerBagMixtoListo;

        return $this;
    }

    /**
     * Get rewardCriteriaPerBagMixtoListo
     *
     * @return float 
     */
    public function getRewardCriteriaPerBagMixtoListo()
    {
        return $this->rewardCriteriaPerBagMixtoListo;
    }

    /**
     * Set sale
     *
     * @param \AppBundle\Entity\Sale $sale
     * @return PromoCupon
     */
    public function setSale(\AppBundle\Entity\Sale $sale = null)
    {
        $this->sale = $sale;

        return $this;
    }

    /**
     * Get sale
     *
     * @return \AppBundle\Entity\Sale 
     */
    public function getSale()
    {
        return $this->sale;
    }

    /**
     * Set staffUsedBy
     *
     * @param \AppBundle\Entity\Staff $staffUsedBy
     * @return PromoCupon
     */
    public function setStaffUsedBy(\AppBundle\Entity\Staff $staffUsedBy = null)
    {
        $this->staffUsedBy = $staffUsedBy;

        return $this;
    }

    /**
     * Get staffUsedBy
     *
     * @return \AppBundle\Entity\Staff 
     */
    public function getStaffUsedBy()
    {
        return $this->staffUsedBy;
    }
}
