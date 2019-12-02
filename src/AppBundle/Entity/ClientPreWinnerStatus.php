<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClientPreWinnerStatus
 */
class ClientPreWinnerStatus
{
    /**
     * @var integer
     */
    private $clientPreWinnerStatusId;

    /**
     * @var string
     */
    private $name;


    /**
     * Get clientPreWinnerStatusId
     *
     * @return integer 
     */
    public function getClientPreWinnerStatusId()
    {
        return $this->clientPreWinnerStatusId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ClientPreWinnerStatus
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

    /**
     * Get name
     *
     * @return string 
     */
    public function __toString()
    {
        return $this->name;
    }
}
