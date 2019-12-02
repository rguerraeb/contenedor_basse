<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StaffGoalStatus
 */
class StaffGoalStatus
{
    /**
     * @var integer
     */
    private $staffGoalStatusId;

    /**
     * @var string
     */
    private $name;


    /**
     * Get staffGoalStatusId
     *
     * @return integer 
     */
    public function getStaffGoalStatusId()
    {
        return $this->staffGoalStatusId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return StaffGoalStatus
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
     * Get string version
     *
     * @return string 
     */
    public function __toString()
    {
        return $this->name;
    }
}
