<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModuleCatalog
 */
class ModuleCatalog
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $parentId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $urlAccess;

    /**
     * @var float
     */
    private $orderModule;

    /**
     * @var boolean
     */
    private $visible;

    /**
     * @var string
     */
    private $moduleIcon;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \AppBundle\Entity\User
     */
    private $createdBy;

    /**
     * @var \AppBundle\Entity\User
     */
    private $updatedBy;


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
     * Set parentId
     *
     * @param integer $parentId
     * @return ModuleCatalog
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId
     *
     * @return integer 
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ModuleCatalog
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
     * Set description
     *
     * @param string $description
     * @return ModuleCatalog
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set urlAccess
     *
     * @param string $urlAccess
     * @return ModuleCatalog
     */
    public function setUrlAccess($urlAccess)
    {
        $this->urlAccess = $urlAccess;

        return $this;
    }

    /**
     * Get urlAccess
     *
     * @return string 
     */
    public function getUrlAccess()
    {
        return $this->urlAccess;
    }

    /**
     * Set orderModule
     *
     * @param float $orderModule
     * @return ModuleCatalog
     */
    public function setOrderModule($orderModule)
    {
        $this->orderModule = $orderModule;

        return $this;
    }

    /**
     * Get orderModule
     *
     * @return float 
     */
    public function getOrderModule()
    {
        return $this->orderModule;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     * @return ModuleCatalog
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean 
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set moduleIcon
     *
     * @param string $moduleIcon
     * @return ModuleCatalog
     */
    public function setModuleIcon($moduleIcon)
    {
        $this->moduleIcon = $moduleIcon;

        return $this;
    }

    /**
     * Get moduleIcon
     *
     * @return string 
     */
    public function getModuleIcon()
    {
        return $this->moduleIcon;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return ModuleCatalog
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return ModuleCatalog
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set createdBy
     *
     * @param \AppBundle\Entity\User $createdBy
     * @return ModuleCatalog
     */
    public function setCreatedBy(\AppBundle\Entity\User $createdBy = null)
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
     * Set updatedBy
     *
     * @param \AppBundle\Entity\User $updatedBy
     * @return ModuleCatalog
     */
    public function setUpdatedBy(\AppBundle\Entity\User $updatedBy = null)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return \AppBundle\Entity\User 
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }
    
    public function __toString() {
    	return $this->getName();
    }
}
