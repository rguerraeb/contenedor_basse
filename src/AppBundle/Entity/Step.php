<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Step
 */
class Step
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $step;


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
     * Set step
     *
     * @param string $step
     * @return Step
     */
    public function setStep($step)
    {
        $this->step = $step;

        return $this;
    }

    /**
     * Get step
     *
     * @return string 
     */
    public function getStep()
    {
        return $this->step;
    }
}
