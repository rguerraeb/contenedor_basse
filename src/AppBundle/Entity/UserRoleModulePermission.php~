<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserRoleModulePermission
 */
class UserRoleModulePermission
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $viewModule;

    /**
     * @var boolean
     */
    private $readPermission;

    /**
     * @var boolean
     */
    private $writePermission;

    /**
     * @var boolean
     */
    private $editPermission;

    /**
     * @var boolean
     */
    private $deletePermission;

    /**
     * @var boolean
     */
    private $mainModule;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \AppBundle\Entity\ModuleCatalog
     */
    private $module;

    /**
     * @var \AppBundle\Entity\User
     */
    private $createdBy;

    /**
     * @var \AppBundle\Entity\User
     */
    private $updatedBy;

    /**
     * @var \AppBundle\Entity\UserRole
     */
    private $userRole;


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
     * Set viewModule
     *
     * @param boolean $viewModule
     * @return UserRoleModulePermission
     */
    public function setViewModule($viewModule)
    {
        $this->viewModule = $viewModule;

        return $this;
    }

    /**
     * Get viewModule
     *
     * @return boolean 
     */
    public function getViewModule()
    {
        return $this->viewModule;
    }

    /**
     * Set readPermission
     *
     * @param boolean $readPermission
     * @return UserRoleModulePermission
     */
    public function setReadPermission($readPermission)
    {
        $this->readPermission = $readPermission;

        return $this;
    }

    /**
     * Get readPermission
     *
     * @return boolean 
     */
    public function getReadPermission()
    {
        return $this->readPermission;
    }

    /**
     * Set writePermission
     *
     * @param boolean $writePermission
     * @return UserRoleModulePermission
     */
    public function setWritePermission($writePermission)
    {
        $this->writePermission = $writePermission;

        return $this;
    }

    /**
     * Get writePermission
     *
     * @return boolean 
     */
    public function getWritePermission()
    {
        return $this->writePermission;
    }

    /**
     * Set editPermission
     *
     * @param boolean $editPermission
     * @return UserRoleModulePermission
     */
    public function setEditPermission($editPermission)
    {
        $this->editPermission = $editPermission;

        return $this;
    }

    /**
     * Get editPermission
     *
     * @return boolean 
     */
    public function getEditPermission()
    {
        return $this->editPermission;
    }

    /**
     * Set deletePermission
     *
     * @param boolean $deletePermission
     * @return UserRoleModulePermission
     */
    public function setDeletePermission($deletePermission)
    {
        $this->deletePermission = $deletePermission;

        return $this;
    }

    /**
     * Get deletePermission
     *
     * @return boolean 
     */
    public function getDeletePermission()
    {
        return $this->deletePermission;
    }

    /**
     * Set mainModule
     *
     * @param boolean $mainModule
     * @return UserRoleModulePermission
     */
    public function setMainModule($mainModule)
    {
        $this->mainModule = $mainModule;

        return $this;
    }

    /**
     * Get mainModule
     *
     * @return boolean 
     */
    public function getMainModule()
    {
        return $this->mainModule;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return UserRoleModulePermission
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
     * @return UserRoleModulePermission
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
     * Set module
     *
     * @param \AppBundle\Entity\ModuleCatalog $module
     * @return UserRoleModulePermission
     */
    public function setModule(\AppBundle\Entity\ModuleCatalog $module = null)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get module
     *
     * @return \AppBundle\Entity\ModuleCatalog 
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set createdBy
     *
     * @param \AppBundle\Entity\User $createdBy
     * @return UserRoleModulePermission
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
     * @return UserRoleModulePermission
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

    /**
     * Set userRole
     *
     * @param \AppBundle\Entity\UserRole $userRole
     * @return UserRoleModulePermission
     */
    public function setUserRole(\AppBundle\Entity\UserRole $userRole = null)
    {
        $this->userRole = $userRole;

        return $this;
    }

    /**
     * Get userRole
     *
     * @return \AppBundle\Entity\UserRole 
     */
    public function getUserRole()
    {
        return $this->userRole;
    }
}
