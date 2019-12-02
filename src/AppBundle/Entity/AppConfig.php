<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AppConfig
 */
class AppConfig
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $pingRequestWaitingTime;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set pingRequestWaitingTime
     *
     * @param integer $pingRequestWaitingTime
     * @return AppConfig
     */
    public function setPingRequestWaitingTime($pingRequestWaitingTime)
    {
        $this->pingRequestWaitingTime = $pingRequestWaitingTime;

        return $this;
    }

    /**
     * Get pingRequestWaitingTime
     *
     * @return integer 
     */
    public function getPingRequestWaitingTime()
    {
        return $this->pingRequestWaitingTime;
    }
}
