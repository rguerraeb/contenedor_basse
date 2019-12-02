<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GoalJobPosition
 */
class GoalJobPosition
{
    /**
     * @var integer
     */
    private $goalJobPositionId;

    /**
     * @var \AppBundle\Entity\Goal
     */
    private $goal;

    /**
     * @var \AppBundle\Entity\JobPosition
     */
    private $jobPosition;


    /**
     * Get goalJobPositionId
     *
     * @return integer 
     */
    public function getGoalJobPositionId()
    {
        return $this->goalJobPositionId;
    }

    /**
     * Set goal
     *
     * @param \AppBundle\Entity\Goal $goal
     * @return GoalJobPosition
     */
    public function setGoal(\AppBundle\Entity\Goal $goal = null)
    {
        $this->goal = $goal;

        return $this;
    }

    /**
     * Get goal
     *
     * @return \AppBundle\Entity\Goal 
     */
    public function getGoal()
    {
        return $this->goal;
    }

    /**
     * Set jobPosition
     *
     * @param \AppBundle\Entity\JobPosition $jobPosition
     * @return GoalJobPosition
     */
    public function setJobPosition(\AppBundle\Entity\JobPosition $jobPosition = null)
    {
        $this->jobPosition = $jobPosition;

        return $this;
    }

    /**
     * Get jobPosition
     *
     * @return \AppBundle\Entity\JobPosition 
     */
    public function getJobPosition()
    {
        return $this->jobPosition;
    }
}
