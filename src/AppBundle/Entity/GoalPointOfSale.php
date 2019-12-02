<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GoalPointOfSale
 */
class GoalPointOfSale
{
    /**
     * @var integer
     */
    private $goalPointOfSaleId;

    /**
     * @var \AppBundle\Entity\Goal
     */
    private $goal;

    /**
     * @var \AppBundle\Entity\PointOfSale
     */
    private $pointOfSale;


    /**
     * Get goalPointOfSaleId
     *
     * @return integer 
     */
    public function getGoalPointOfSaleId()
    {
        return $this->goalPointOfSaleId;
    }

    /**
     * Set goal
     *
     * @param \AppBundle\Entity\Goal $goal
     * @return GoalPointOfSale
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
     * Set pointOfSale
     *
     * @param \AppBundle\Entity\PointOfSale $pointOfSale
     * @return GoalPointOfSale
     */
    public function setPointOfSale(\AppBundle\Entity\PointOfSale $pointOfSale = null)
    {
        $this->pointOfSale = $pointOfSale;

        return $this;
    }

    /**
     * Get pointOfSale
     *
     * @return \AppBundle\Entity\PointOfSale 
     */
    public function getPointOfSale()
    {
        return $this->pointOfSale;
    }
}
