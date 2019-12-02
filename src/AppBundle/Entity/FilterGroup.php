<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FilterGroup
 */
class FilterGroup
{
    /**
     * @var integer
     */
    private $filterGroupId;

    /**
     * @var string
     */
    private $name;


    /**
     * Get filterGroupId
     *
     * @return integer 
     */
    public function getFilterGroupId()
    {
        return $this->filterGroupId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return FilterGroup
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
     * Get name
     *
     * @return string 
     */
    public function __toString()
    {
        return $this->name;
    }
    /**
     * @var \AppBundle\Entity\PurchasedProductList
     */
    private $ppl;


    /**
     * Set ppl
     *
     * @param \AppBundle\Entity\PurchasedProductList $ppl
     * @return FilterGroup
     */
    public function setPpl(\AppBundle\Entity\PurchasedProductList $ppl = null)
    {
        $this->ppl = $ppl;

        return $this;
    }

    /**
     * Get ppl
     *
     * @return \AppBundle\Entity\PurchasedProductList 
     */
    public function getPpl()
    {
        return $this->ppl;
    }
}
