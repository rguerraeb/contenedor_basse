<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PrizeStep
 */
class PrizeStep
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Step
     */
    private $step;

    /**
     * @var \AppBundle\Entity\Prize
     */
    private $prize;


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
     * @param \AppBundle\Entity\Step $step
     * @return PrizeStep
     */
    public function setStep(\AppBundle\Entity\Step $step = null)
    {
        $this->step = $step;

        return $this;
    }

    /**
     * Get step
     *
     * @return \AppBundle\Entity\Step 
     */
    public function getStep()
    {
        return $this->step;
    }

    /**
     * Set prize
     *
     * @param \AppBundle\Entity\Prize $prize
     * @return PrizeStep
     */
    public function setPrize(\AppBundle\Entity\Prize $prize = null)
    {
        $this->prize = $prize;

        return $this;
    }

    /**
     * Get prize
     *
     * @return \AppBundle\Entity\Prize 
     */
    public function getPrize()
    {
        return $this->prize;
    }
}
