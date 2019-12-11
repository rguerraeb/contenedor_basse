<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WinnParticipant
 */
class WinnParticipant
{
    /**
     * @var integer
     */
    private $winnParticipantId;

    /**
     * @var integer
     */
    private $statusId;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \AppBundle\Entity\StaffCode
     */
    private $staffCode;


    /**
     * Get winnParticipantId
     *
     * @return integer 
     */
    public function getWinnParticipantId()
    {
        return $this->winnParticipantId;
    }

    /**
     * Set statusId
     *
     * @param integer $statusId
     * @return WinnParticipant
     */
    public function setStatusId($statusId)
    {
        $this->statusId = $statusId;

        return $this;
    }

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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return WinnParticipant
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return WinnParticipant
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set staffCode
     *
     * @param \AppBundle\Entity\StaffCode $staffCode
     * @return WinnParticipant
     */
    public function setStaffCode(\AppBundle\Entity\StaffCode $staffCode = null)
    {
        $this->staffCode = $staffCode;

        return $this;
    }

    /**
     * Get staffCode
     *
     * @return \AppBundle\Entity\StaffCode 
     */
    public function getStaffCode()
    {
        return $this->staffCode;
    }
}
