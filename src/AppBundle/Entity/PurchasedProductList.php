<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PurchasedProductList
 */
class PurchasedProductList
{
    /**
     * @var integer
     */
    private $purchasedProductListId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \DateTime
     */
    private $createdAt;

	 /**
	  * @var PurchasedProductCategory
	  */

    private $purchasedProductCategory;


	/**
	 * @return int
	 */
	public function getPurchasedProductListId() {
		return $this->purchasedProductListId;
	}

	/**
	 * @param int $purchasedProductListId
	 */
	public function setPurchasedProductListId( $purchasedProductListId ) {
		$this->purchasedProductListId = $purchasedProductListId;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( $name ) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription( $description ) {
		$this->description = $description;
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


}
