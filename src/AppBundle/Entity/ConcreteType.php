<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConcreteType
 */
class ConcreteType
{
    /**
     * @var integer
     */
    private $concreteTypeId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var boolean
     */
    private $published;


    /**
     * Get concreteTypeId
     *
     * @return integer 
     */
    public function getConcreteTypeId()
    {
        return $this->concreteTypeId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ConcreteType
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
     * Set published
     *
     * @param boolean $published
     * @return ConcreteType
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean 
     */
    public function getPublished()
    {
        return $this->published;
    }
}
