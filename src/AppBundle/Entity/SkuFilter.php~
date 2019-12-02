<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SkuFilter
 */
class SkuFilter
{
    /**
     * @var integer
     */
    private $skuFilterId;

    /**
     * @var \AppBundle\Entity\Filter
     */
    private $filter;

    /**
     * @var \AppBundle\Entity\Sku
     */
    private $sku;


    /**
     * Get skuFilterId
     *
     * @return integer 
     */
    public function getSkuFilterId()
    {
        return $this->skuFilterId;
    }

    /**
     * Set filter
     *
     * @param \AppBundle\Entity\Filter $filter
     * @return SkuFilter
     */
    public function setFilter(\AppBundle\Entity\Filter $filter = null)
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * Get filter
     *
     * @return \AppBundle\Entity\Filter 
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * Set sku
     *
     * @param \AppBundle\Entity\Sku $sku
     * @return SkuFilter
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
}
