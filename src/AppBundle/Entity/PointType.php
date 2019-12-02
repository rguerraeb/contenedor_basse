<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PointType
 */
class PointType
{
    /**
     * @var integer
     */
    private $pointTypeId;

    /**
     * @var string
     */
    private $pointType;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var integer
     */
    private $createdBy;


    /**
     * Get pointTypeId
     *
     * @return integer 
     */
    public function getPointTypeId()
    {
        return $this->pointTypeId;
    }

    /**
     * Set pointType
     *
     * @param string $pointType
     * @return PointType
     */
    public function setPointType($pointType)
    {
        $this->pointType = $pointType;

        return $this;
    }

    /**
     * Get pointType
     *
     * @return string 
     */
    public function getPointType()
    {
        return $this->pointType;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return PointType
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
     * @param integer $createdBy
     * @return PointType
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return integer 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
}
