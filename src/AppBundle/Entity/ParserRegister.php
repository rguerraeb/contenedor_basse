<?php

namespace AppBundle\Entity;

/**
 * ParserRegister
 */
class ParserRegister
{

    /**
     * @var integer
     */
    private $parserRegisterId;

    /**
     * @var string
     */
    private $fileName;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \AppBundle\Entity\User
     */
    private $createdBy;


    /**
     * Get parserRegisterId
     *
     * @return integer 
     */
    public function getParserRegisterId()
    {
        return $this->parserRegisterId;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     * @return ParserRegister
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string 
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return ParserRegister
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
     * @param \AppBundle\Entity\User $createdBy
     * @return ParserRegister
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \AppBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
    /**
     * @var \AppBundle\Entity\ParserType
     */
    private $parserType;


    /**
     * Set parserType
     *
     * @param \AppBundle\Entity\ParserType $parserType
     * @return ParserRegister
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
