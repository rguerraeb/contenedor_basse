<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StaffGoalNotification
 */
class StaffGoalNotification
{
    /**
     * @var integer
     */
    private $staffGoalNotificationId;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \AppBundle\Entity\Goal
     */
    private $goal;

    /**
     * @var \AppBundle\Entity\Notification
     */
    private $notification;

    /**
     * @var \AppBundle\Entity\Staff
     */
    private $staff;


    /**
     * Get staffGoalNotificationId
     *
     * @return integer 
     */
    public function getStaffGoalNotificationId()
    {
        return $this->staffGoalNotificationId;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return StaffGoalNotification
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
     * Set goal
     *
     * @param \AppBundle\Entity\Goal $goal
     * @return StaffGoalNotification
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
     * Set notification
     *
     * @param \AppBundle\Entity\Notification $notification
     * @return StaffGoalNotification
     */
    public function setNotification(\AppBundle\Entity\Notification $notification = null)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * Get notification
     *
     * @return \AppBundle\Entity\Notification 
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * Set staff
     *
     * @param \AppBundle\Entity\Staff $staff
     * @return StaffGoalNotification
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
}
