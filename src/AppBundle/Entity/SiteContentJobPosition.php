<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SiteContentJobPosition
 */
class SiteContentJobPosition
{
    /**
     * @var integer
     */
    private $siteContentJobPositionId;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \AppBundle\Entity\JobPosition
     */
    private $jobPosition;

    /**
     * @var \AppBundle\Entity\SiteContent
     */
    private $siteContent;


    /**
     * Get siteContentJobPositionId
     *
     * @return integer 
     */
    public function getSiteContentJobPositionId()
    {
        return $this->siteContentJobPositionId;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return SiteContentJobPosition
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
     * Set jobPosition
     *
     * @param \AppBundle\Entity\JobPosition $jobPosition
     * @return SiteContentJobPosition
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

    /**
     * Set siteContent
     *
     * @param \AppBundle\Entity\SiteContent $siteContent
     * @return SiteContentJobPosition
     */
    public function setSiteContent(\AppBundle\Entity\SiteContent $siteContent = null)
    {
        $this->siteContent = $siteContent;
    
        return $this;
    }

    /**
     * Get siteContent
     *
     * @return \AppBundle\Entity\SiteContent 
     */
    public function getSiteContent()
    {
        return $this->siteContent;
    }
}
