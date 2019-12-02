<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SkuCategory
 */
class SkuCategory
{
    /**
     * @var integer
     */
    private $skuCategoryId;

    /**
     * @var string
     */
    private $name;


    /**
     * Get skuCategoryId
     *
     * @return integer 
     */
    public function getSkuCategoryId()
    {
        return $this->skuCategoryId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return SkuCategory
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
}
