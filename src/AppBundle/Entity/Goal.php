<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Goal
 */
class Goal
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $start;

    /**
     * @var \DateTime
     */
    private $end;

    /**
     * @var integer
     */
    private $points;

    /**
     * @var string
     */
    private $message;

    /**
     * @var \AppBundle\Entity\GoalType
     */
    private $goalType;

    /**
     * @var \AppBundle\Entity\Prize
     */
    private $prize;

    /**
     * @var \AppBundle\Entity\GoalStatus
     */
    private $goalStatus;

    /**
     * @var integer
     */
    private $quantity;


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
     * Set name
     *
     * @param string $name
     * @return Goal
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
     * Set description
     *
     * @param string $description
     * @return Goal
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Goal
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
     * Set start
     *
     * @param \DateTime $start
     * @return Goal
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime 
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     * @return Goal
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime 
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set points
     *
     * @param integer $points
     * @return Goal
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer 
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Goal
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set goalType
     *
     * @param \AppBundle\Entity\GoalType $goalType
     * @return Goal
     */
    public function setGoalType(\AppBundle\Entity\GoalType $goalType = null)
    {
        $this->goalType = $goalType;

        return $this;
    }

    /**
     * Get goalType
     *
     * @return \AppBundle\Entity\GoalType 
     */
    public function getGoalType()
    {
        return $this->goalType;
    }

    /**
     * Set prize
     *
     * @param \AppBundle\Entity\Prize $prize
     * @return Goal
     */
    public function setPrize(\AppBundle\Entity\Prize $prize = null)
    {
        $this->prize = $prize;

        return $this;
    }

    /**
     * Get prize
     *
     * @return \AppBundle\Entity\Prize 
     */
    public function getPrize()
    {
        return $this->prize;
    }

    /**
     * Set goalStatus
     *
     * @param \AppBundle\Entity\GoalStatus $goalStatus
     * @return Goal
     */
    public function setGoalStatus(\AppBundle\Entity\GoalStatus $goalStatus = null)
    {
        $this->goalStatus = $goalStatus;

        return $this;
    }

    /**
     * Get goalStatus
     *
     * @return \AppBundle\Entity\GoalStatus 
     */
    public function getGoalStatus()
    {
        return $this->goalStatus;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return Goal
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
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

    /**
     * Get job positions ids for unit type goal
     *
     * @return array ids of jobPositions for that make unit type goals
     */
    public function getUnitJps() {
        return array(
            5, 6, 4, 8
        );
    }

    /**
     * Get job positions ids for percentage type goal
     *
     * @return array ids of jobPositions for that make percentage type goals
     */
    public function getPercentageJps() {
        return array(
            2
        );
    }

    /**
     * Sets null the invalid prize for the type of goal prize
     */
    public function setCorrectPrize() {
        // Based on type remove other fields
        $type = $this->goalType->getGoalTypeId();
        if ($type == 1) {
            $this->prize = NULL;
            $this->message = NULL;
        }
        else if ($type == 2) {
            $this->points = NULL;
            $this->message = NULL;
        }
        else if ($type == 3) {
            $this->points = NULL;
            $this->prize = NULL;
        }
    }

    /**
     * Gets the type of based on jobPositions
     *
     * @param array $jobPositions jobPositions to check type of goal
     * @return int type of goal. -1: N/A, 0: percentage type, 1: unit type
     */
    public function goalJpType($jobPositions) {
        $ids = $this->jobPositionsToIds($jobPositions);

        if (array_intersect($ids, $this->getUnitJps())) {
            // Its unit type
            return 1;
        }
        else if (array_intersect($ids, $this->getPercentageJps())) {
            // It's percentage type
            return 0;
        }

        return -1;
    }

    /**
     * Sets quantity attribute using average and the criteria
     *
     * @param float $average average of sales to get quantity value
     * @param float $criteria percentage or units for quantity
     * @param array $jobPositions jobPositions
     */
    public function setQuantityByCriteria($average, $criteria, $jobPositions) {
        $jpType = $this->goalJpType($jobPositions);
        if ($jpType == 1) {
            // Unit type goal
            $this->quantity = $criteria + $average;
        }
        else if ($jpType == 0) {
            // Percentage type goal
            $this->quantity = ceil($average * (1 + $criteria));
        }
    }

    /**
     * From array of job positions gets their ids and returns them in an array
     *
     * @param array JobPositions to get their ids
     * @return arary ids of jobPositions
     */
    private function jobPositionsToIds($jobPositions) {
        $ids = array();
        foreach ($jobPositions as $jobPosition) {
            array_push($ids, $jobPosition->getId());
        }

        return $ids;
    }
}
