<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Status
 */
class Status
{
    /**
     * @var integer
     */
    private $statusId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $forTable;

    /**
     * @var binary
     */
    private $active;


    /**
     * Get statusId
     *
     * @return integer 
     */
    public function getStatusId()
    {
        return $this->statusId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Status
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
     * @return Status
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
     * @param binary $active
     * @return Status
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return binary 
     */
    public function getActive()
    {
        return $this->active;
    }
}
