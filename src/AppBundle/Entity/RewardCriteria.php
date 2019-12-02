<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RewardCriteria
 */
class RewardCriteria
{
    /**
     * @var integer
     */
    private $idRewardCriteria;

    /**
     * @var string
     */
    private $mathematicalOperator;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var string
     */
    private $createdBy;

    /**
     * @var \AppBundle\Entity\FilterGroup
     */
    private $filterGroup;

    /**
     * @var \AppBundle\Entity\JobPosition
     */
    private $jobPosition;


    /**
     * Get idRewardCriteria
     *
     * @return integer 
     */
    public function getIdRewardCriteria()
    {
        return $this->idRewardCriteria;
    }

    /**
     * Set mathematicalOperator
     *
     * @param string $mathematicalOperator
     * @return RewardCriteria
     */
    public function setMathematicalOperator($mathematicalOperator)
    {
        $this->mathematicalOperator = $mathematicalOperator;

        return $this;
    }

    /**
     * Get mathematicalOperator
     *
     * @return string 
     */
    public function getMathematicalOperator()
    {
        return $this->mathematicalOperator;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return RewardCriteria
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
     * Set createdBy
     *
     * @param string $createdBy
     * @return RewardCriteria
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return string 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set filterGroup
     *
     * @param \AppBundle\Entity\FilterGroup $filterGroup
     * @return RewardCriteria
     */
    public function setFilterGroup(\AppBundle\Entity\FilterGroup $filterGroup = null)
    {
        $this->filterGroup = $filterGroup;

        return $this;
    }

    /**
     * Get filterGroup
     *
     * @return \AppBundle\Entity\FilterGroup 
     */
    public function getFilterGroup()
    {
        return $this->filterGroup;
    }

    /**
     * Set jobPosition
     *
     * @param \AppBundle\Entity\JobPosition $jobPosition
     * @return RewardCriteria
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
