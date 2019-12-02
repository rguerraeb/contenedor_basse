<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PurchasedProductDetail
 */
class PurchasedProductDetail
{
    /**
     * @var integer
     */
    private $purchasedProductDetailId;

    /**
     * @var integer
     */
    private $amount;

    /**
     * @var float
     */
    private $value;

    /**
     * @var \DateTime
     */
    private $createdAt;

	 /**
	  * @var PurchasedProductCategory
	  */

    private $purchasedProductCategory;

	/**
	 * @var PurchasedProductList
	 */

	private $purchasedProductList;

	/**
	 * @var RegisterPending
	 */

	private $registerPending;

	/**
	 * @var integer
	 */
	private $sale;
	
	private $rewardCriteria;
	
	private $totalPoints;
	
	/**
	 * 
	 * @var Sku
	 */
	private $sku;
	
	/**
     * @return \AppBundle\Entity\Sku
     */
    public function getSku ()
    {
        return $this->sku;
    }

    /**
     * @param \AppBundle\Entity\Sku $sku
     */
    public function setSku ($sku)
    {
        $this->sku = $sku;
        return $this;
    }

    public function setRewardCriteria($rewardCriteria) {
	    $this->rewardCriteria = $rewardCriteria;
	}
	
	public function getRewardCriteria( ) {
	    return $this->rewardCriteria;
	}
	
	public function setTotalPoints($totalPoints) {
	    $this->totalPoints = $totalPoints;
	}
	
	public function getTotalPoints() {
	    return $this->totalPoints;
	}

	/**
	 * @return int
	 */
	public function getPurchasedProductDetailId() {
		return $this->purchasedProductDetailId;
	}

	/**
	 * @param int $purchasedProductDetailId
	 */
	public function setPurchasedProductDetailId( $purchasedProductDetailId ) {
		$this->purchasedProductDetailId = $purchasedProductDetailId;
	}

	/**
	 * @return int
	 */
	public function getAmount() {
		return $this->amount;
	}

	/**
	 * @param int $amount
	 */
	public function setAmount( $amount ) {
		$this->amount = $amount;
	}

	/**
	 * @return float
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @param float $value
	 */
	public function setValue( $value ) {
		$this->value = $value;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreatedAt() {
		return $this->createdAt;
	}

	/**
	 * @param \DateTime $createdAt
	 */
	public function setCreatedAt( $createdAt ) {
		$this->createdAt = $createdAt;
	}

	/**
	 * @return PurchasedProductCategory
	 */
	public function getPurchasedProductCategory() {
		return $this->purchasedProductCategory;
	}

	/**
	 * @param PurchasedProductCategory $purchasedProductCategory
	 */
	public function setPurchasedProductCategory( $purchasedProductCategory ) {
		$this->purchasedProductCategory = $purchasedProductCategory;
	}

	/**
	 * @return PurchasedProductList
	 */
	public function getPurchasedProductList() {
		return $this->purchasedProductList;
	}

	/**
	 * @param PurchasedProductList $purchasedProductList
	 */
	public function setPurchasedProductList( $purchasedProductList ) {
		$this->purchasedProductList = $purchasedProductList;
	}

	/**
	 * @return RegisterPending
	 */
	public function getRegisterPending() {
		return $this->registerPending;
	}

	/**
	 * @param RegisterPending $registerPending
	 */
	public function setRegisterPending( $registerPending ) {
		$this->registerPending = $registerPending;
	}

	/**
	 * @return int
	 */
	public function getSale() {
		return $this->sale;
	}

	/**
	 * @param int $sale
	 */
	public function setSale( $sale ) {
		$this->sale = $sale;
	}

}
