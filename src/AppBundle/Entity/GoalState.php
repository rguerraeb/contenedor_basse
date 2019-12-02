<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GoalState
 */
class GoalState
{
    /**
     * @var integer
     */
    private $goalStateId;

    /**
     * @var \AppBundle\Entity\Goal
     */
    private $goal;

    /**
     * @var \AppBundle\Entity\State
     */
    private $state;


    /**
     * Get goalStateId
     *
     * @return integer 
     */
    public function getGoalStateId()
    {
        return $this->goalStateId;
    }

    /**
     * Set goal
     *
     * @param \AppBundle\Entity\Goal $goal
     * @return GoalState
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
     * Set state
     *
     * @param \AppBundle\Entity\State $state
     * @return GoalState
     */
    public function setState(\AppBundle\Entity\State $state = null)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return \AppBundle\Entity\State 
     */
    public function getState()
    {
        return $this->state;
    }
}
