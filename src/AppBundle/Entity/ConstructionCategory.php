<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConstructionCategory
 */
class ConstructionCategory
{
    /**
     * @var integer
     */
    private $constructionCategoryId;

    /**
     * @var string
     */
    private $name;
    
    private $display;


  	/**
     * @return mixed
     */
    public function getDisplay ()
    {
        return $this->display;
    }

    /**
     * @param mixed $display
     */
    public function setDisplay ($display)
    {
        $this->display = $display;
        return $this;
    }

    public function __toString(){
       
        return $this->name;
    }

    /**
     * Get constructionCategoryId
     *
     * @return integer 
     */
    public function getConstructionCategoryId()
    {
        return $this->constructionCategoryId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ConstructionCategory
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
