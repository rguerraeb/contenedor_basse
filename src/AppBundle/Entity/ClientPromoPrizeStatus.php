<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClientPromoPrizeStatus
 */
class ClientPromoPrizeStatus
{
    /**
     * @var integer
     */
    private $clientPromoPrizeStatusId;

    /**
     * @var string
     */
    private $name;


    /**
     * Get clientPromoPrizeStatusId
     *
     * @return integer 
     */
    public function getClientPromoPrizeStatusId()
    {
        return $this->clientPromoPrizeStatusId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ClientPromoPrizeStatus
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
}
