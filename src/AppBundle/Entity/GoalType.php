<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GoalType
 */
class GoalType
{
    /**
     * @var integer
     */
    private $goalTypeId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \DateTime
     */
    private $createdAt;


    /**
     * Get goalTypeId
     *
     * @return integer 
     */
    public function getGoalTypeId()
    {
        return $this->goalTypeId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return GoalType
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return GoalType
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * To string
     *
     * @return string 
     */
    public function __toString()
    {
        return $this->name;
    }
}
