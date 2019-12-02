<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Filter
 */
class Filter
{
    /**
     * @var integer
     */
    private $filterId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $filterCode;

    /**
     * @var integer
     */
    private $filterValue;

    /**
     * @var \AppBundle\Entity\FilterGroup
     */
    private $filterGroup;


    /**
     * Get filterId
     *
     * @return integer 
     */
    public function getFilterId()
    {
        return $this->filterId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Filter
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
     * Set filterCode
     *
     * @param string $filterCode
     * @return Filter
     */
    public function setFilterCode($filterCode)
    {
        $this->filterCode = $filterCode;

        return $this;
    }

    /**
     * Get filterCode
     *
     * @return string 
     */
    public function getFilterCode()
    {
        return $this->filterCode;
    }

    /**
     * Set filterValue
     *
     * @param integer $filterValue
     * @return Filter
     */
    public function setFilterValue($filterValue)
    {
        $this->filterValue = $filterValue;

        return $this;
    }

    /**
     * Get filterValue
     *
     * @return integer 
     */
    public function getFilterValue()
    {
        return $this->filterValue;
    }

    /**
     * Set filterGroup
     *
     * @param \AppBundle\Entity\FilterGroup $filterGroup
     * @return Filter
     */
    public function setFilterGroup(\AppBundle\Entity\FilterGroup $filterGroup = null)
    {
        $this->filterGroup = $filterGroup;

        return $this;
    }

    /**
     * Get filterGroup
     *
     * @return \AppBundle\Entity\FilterGroup 
     */
    public function getFilterGroup()
    {
        return $this->filterGroup;
    }
}
