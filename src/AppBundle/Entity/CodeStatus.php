<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CodeStatus
 */
class CodeStatus
{
    /**
     * @var integer
     */
    private $codeStatusId;

    /**
     * @var string
     */
    private $name;

    /**
     * Get codeStatusId
     *
     * @return integer 
     */
    public function getCodeStatusId()
    {
        return $this->codeStatusId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return CodeStatus
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    public function __toString() {
    	return $this->getName();
    }
}
