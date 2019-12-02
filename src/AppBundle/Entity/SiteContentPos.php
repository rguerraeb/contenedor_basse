<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SiteContentPos
 */
class SiteContentPos
{
    /**
     * @var integer
     */
    private $siteContentPosId;

    /**
     * @var integer
     */
    private $siteContentId;

    /**
     * @var integer
     */
    private $posId;

    /**
     * @var \DateTime
     */
    private $createdAt;


    /**
     * Get siteContentPosId
     *
     * @return integer 
     */
    public function getSiteContentPosId()
    {
        return $this->siteContentPosId;
    }

    /**
     * Set siteContentId
     *
     * @param integer $siteContentId
     * @return SiteContentPos
     */
    public function setSiteContentId($siteContentId)
    {
        $this->siteContentId = $siteContentId;
    
        return $this;
    }

    /**
     * Get siteContentId
     *
     * @return integer 
     */
    public function getSiteContentId()
    {
        return $this->siteContentId;
    }

    /**
     * Set posId
     *
     * @param integer $posId
     * @return SiteContentPos
     */
    public function setPosId($posId)
    {
        $this->posId = $posId;
    
        return $this;
    }

    /**
     * Get posId
     *
     * @return integer 
     */
    public function getPosId()
    {
        return $this->posId;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return SiteContentPos
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
}
