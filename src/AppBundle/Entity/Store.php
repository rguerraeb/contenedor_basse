<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Store
 */
class Store
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $storeName;

    /**
     * @var \AppBundle\Entity\Country
     */
    private $country;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set storeName
     *
     * @param string $storeName
     * @return Store
     */
    public function setStoreName($storeName)
    {
        $this->storeName = $storeName;

        return $this;
    }

    /**
     * Get storeName
     *
     * @return string 
     */
    public function getStoreName()
    {
        return $this->storeName;
    }

    /**
     * Set country
     *
     * @param \AppBundle\Entity\Country $country
     * @return Store
     */
    public function setCountry(\AppBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \AppBundle\Entity\Country 
     */
    public function getCountry()
    {
        return $this->country;
    }
}
