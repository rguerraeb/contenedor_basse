<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StaffGoal
 */
class StaffGoal
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $points;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var string
     */
    private $message;

    /**
     * @var \AppBundle\Entity\Goal
     */
    private $goal;

    /**
     * @var \AppBundle\Entity\Staff
     */
    private $staff;

    /**
     * @var \AppBundle\Entity\Prize
     */
    private $prize;

    /**
     * @var \AppBundle\Entity\StaffGoalStatus
     */
    private $staffGoalStatus;

    /**
     * @var string
     */
    private $phone;


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
     * Set points
     *
     * @param integer $points
     * @return StaffGoal
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return StaffGoal
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
     * Set message
     *
     * @param string $message
     * @return StaffGoal
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
     * Set goal
     *
     * @param \AppBundle\Entity\Goal $goal
     * @return StaffGoal
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
     * Set staff
     *
     * @param \AppBundle\Entity\Staff $staff
     * @return StaffGoal
     */
    public function setStaff(\AppBundle\Entity\Staff $staff = null)
    {
        $this->staff = $staff;

        return $this;
    }

    /**
     * Get staff
     *
     * @return \AppBundle\Entity\Staff 
     */
    public function getStaff()
    {
        return $this->staff;
    }

    /**
     * Set prize
     *
     * @param \AppBundle\Entity\Prize $prize
     * @return StaffGoal
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
     * Set staffGoalStatus
     *
     * @param \AppBundle\Entity\StaffGoalStatus $staffGoalStatus
     * @return StaffGoal
     */
    public function setStaffGoalStatus(\AppBundle\Entity\StaffGoalStatus $staffGoalStatus = null)
    {
        $this->staffGoalStatus = $staffGoalStatus;

        return $this;
    }

    /**
     * Get staffGoalStatus
     *
     * @return \AppBundle\Entity\StaffGoalStatus 
     */
    public function getStaffGoalStatus()
    {
        return $this->staffGoalStatus;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return StaffGoal
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }
}
