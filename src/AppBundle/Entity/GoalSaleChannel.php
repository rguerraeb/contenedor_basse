<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GoalSaleChannel
 */
class GoalSaleChannel
{
    /**
     * @var integer
     */
    private $goalSaleChannelId;

    /**
     * @var \AppBundle\Entity\Goal
     */
    private $goal;

    /**
     * @var \AppBundle\Entity\SaleChannel
     */
    private $saleChannel;


    /**
     * Get goalSaleChannelId
     *
     * @return integer 
     */
    public function getGoalSaleChannelId()
    {
        return $this->goalSaleChannelId;
    }

    /**
     * Set goal
     *
     * @param \AppBundle\Entity\Goal $goal
     * @return GoalSaleChannel
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
     * Set saleChannel
     *
     * @param \AppBundle\Entity\SaleChannel $saleChannel
     * @return GoalSaleChannel
     */
    public function setSaleChannel(\AppBundle\Entity\SaleChannel $saleChannel = null)
    {
        $this->saleChannel = $saleChannel;

        return $this;
    }

    /**
     * Get saleChannel
     *
     * @return \AppBundle\Entity\SaleChannel 
     */
    public function getSaleChannel()
    {
        return $this->saleChannel;
    }
}
