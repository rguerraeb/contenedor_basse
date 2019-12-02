<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PromoPrizeStatus
 */
class PromoPrizeStatus
{
    /**
     * @var integer
     */
    private $promoPrizeStatusId;

    /**
     * @var string
     */
    private $name;


    /**
     * Get promoPrizeStatusId
     *
     * @return integer 
     */
    public function getPromoPrizeStatusId()
    {
        return $this->promoPrizeStatusId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return PromoPrizeStatus
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
}
