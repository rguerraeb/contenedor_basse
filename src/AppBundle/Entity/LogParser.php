<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LogParser
 */
class LogParser
{
    /**
     * @var integer
     */
    private $logParserId;

    /**
     * @var string
     */
    private $logText;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var integer
     */
    private $createdBy;

    /**
     * @var \AppBundle\Entity\ParserType
     */
    private $parserType;


    /**
     * Get logParserId
     *
     * @return integer 
     */
    public function getLogParserId()
    {
        return $this->logParserId;
    }

    /**
     * Set logText
     *
     * @param string $logText
     * @return LogParser
     */
    public function setLogText($logText)
    {
        $this->logText = $logText;

        return $this;
    }

    /**
     * Get logText
     *
     * @return string 
     */
    public function getLogText()
    {
        return $this->logText;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return LogParser
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
     * @return LogParser
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

    /**
     * Set parserType
     *
     * @param \AppBundle\Entity\ParserType $parserType
     * @return LogParser
     */
    public function setParserType(\AppBundle\Entity\ParserType $parserType = null)
    {
        $this->parserType = $parserType;

        return $this;
    }

    /**
     * Get parserType
     *
     * @return \AppBundle\Entity\ParserType 
     */
    public function getParserType()
    {
        return $this->parserType;
    }
}
