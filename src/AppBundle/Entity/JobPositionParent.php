<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * JobPositionParent
 */
class JobPositionParent
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \AppBundle\Entity\JobPosition
     */
    private $parent;

    /**
     * @var \AppBundle\Entity\JobPosition
     */
    private $child;


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
     * Set parent
     *
     * @param \AppBundle\Entity\JobPosition $parent
     * @return JobPositionParent
     */
    public function setParent(\AppBundle\Entity\JobPosition $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\JobPosition 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set child
     *
     * @param \AppBundle\Entity\JobPosition $child
     * @return JobPositionParent
     */
    public function setChild(\AppBundle\Entity\JobPosition $child = null)
    {
        $this->child = $child;

        return $this;
    }

    /**
     * Get child
     *
     * @return \AppBundle\Entity\JobPosition 
     */
    public function getChild()
    {
        return $this->child;
    }
}
