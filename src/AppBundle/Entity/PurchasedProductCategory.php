<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PurchasedProductCategory
 */
class PurchasedProductCategory
{
    /**
     * @var integer
     */
    private $purchasedProductCategoryId;

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
    
    private $skuId;

	/**
     * @return mixed
     */
    public function getSkuId ()
    {
        return $this->skuId;
    }

    /**
     * @param mixed $skuId
     */
    public function setSkuId ($skuId)
    {
        $this->skuId = $skuId;
        return $this;
    }

    /**
	 * @return int
	 */
	public function getPurchasedProductCategoryId() {
		return $this->purchasedProductCategoryId;
	}

	/**
	 * @param int $purchasedProductCategoryId
	 */
	public function setPurchasedProductCategoryId( $purchasedProductCategoryId ) {
		$this->purchasedProductCategoryId = $purchasedProductCategoryId;
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



}
