<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MainStatus
 */
class MainStatus
{
    /**
     * @var integer
     */
    private $mainStatusId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $forTable;

    /**
     * @var integer
     */
    private $active;


    /**
     * Get mainStatusId
     *
     * @return integer 
     */
    public function getMainStatusId()
    {
        return $this->mainStatusId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return MainStatus
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
     * Set forTable
     *
     * @param string $forTable
     * @return MainStatus
     */
    public function setForTable($forTable)
    {
        $this->forTable = $forTable;

        return $this;
    }

    /**
     * Get forTable
     *
     * @return string 
     */
    public function getForTable()
    {
        return $this->forTable;
    }

    /**
     * Set active
     *
     * @param integer $active
     * @return MainStatus
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return integer 
     */
    public function getActive()
    {
        return $this->active;
    }

     /**
     * Get string version
     *
     * @return string 
     */
    public function __toString()
    {
        return $this->name;
    }
}
