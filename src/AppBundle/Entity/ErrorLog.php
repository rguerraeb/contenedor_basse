<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ErrorLog
 */
class ErrorLog
{
    /**
     * @var integer
     */
    private $errorLogId;

    /**
     * @var string
     */
    private $errorDescription;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var integer
     */
    private $createdBy;


    /**
     * Get errorLogId
     *
     * @return integer 
     */
    public function getErrorLogId()
    {
        return $this->errorLogId;
    }

    /**
     * Set errorDescription
     *
     * @param string $errorDescription
     * @return ErrorLog
     */
    public function setErrorDescription($errorDescription)
    {
        $this->errorDescription = $errorDescription;

        return $this;
    }

    /**
     * Get errorDescription
     *
     * @return string 
     */
    public function getErrorDescription()
    {
        return $this->errorDescription;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return ErrorLog
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
     * @return ErrorLog
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
