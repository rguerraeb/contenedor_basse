<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Staff
 */
class Staff implements \Serializable
{
    /**
     * @var integer
     */
    private $staffId;

    /**
     * @var string
     */
    private $name;

    /*
     * @var string
     */
    private $citizenId;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $email;
    
    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var integer
     */
    private $createdBy;
    
    public function __construct()
    {
        //$this->staffPointsOfSale = new ArrayCollection();
    }

    /**
     * Get staffId
     *
     * @return integer 
     */
    public function getStaffId()
    {
        return $this->staffId;
    }
    
    public function getUserRole() {
    	return array("name" => "Staff");
    }

    
    /**
     * Set citizenId
     *
     * @param string $citizenId
     * @return Staff
     */
    public function setCitizenId($citizenId)
    {
        $this->citizenId = $citizenId;

        return $this;
    }

    /**
     * Get citizenId
     *
     * @return string 
     */
    public function getCitizenId()
    {
        return $this->citizenId;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Staff
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Staff
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
     * Set email
     *
     * @param string $email
     * @return Staff
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Staff
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
     * Set createdBy
     *
     * @param integer $createdBy
     * @return Staff
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return integer 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    
    public function getRoles(){
        return array('ROLE_ADMINISTRADOR');
    }
    
    public function getSalt() {
    	return null;
    }
    
    public function eraseCredentials() {
    }
    
    /** @see \Serializable::serialize() */
    public function serialize() {
    	return serialize(array(
    			$this->staffId,
    			$this->email
    	));
    }
    
    /** @see \Serializable::unserialize() */
    public function unserialize($serialized) {
    	list (
    			$this->staffId,
    			$this->email
    			) = unserialize($serialized);
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }
    
    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function __toString() {
        if ($this->name) {
            return $this->name;
        }

        return "";
    }

    /**
     * Tells if staff is seller
     *
     * @return boolean if its a seller or no
     */
    public function isSeller(){
        if ($this->jobPosition) {
            $jobPositionId = $this->jobPosition->getId();

            if ($jobPositionId == 5
                || $jobPositionId == 6) {
                // Seller
                return true;
            }
        }

        return false;
    }

    /**
     * Get area code of staff's phone
     *
     * @return string area code of staff's phone
     */
    public function getAreaCode(){
        $prefix = "502";
        if ($this->country) {
            $prefix = $this->country->getPhonePrefix();
        }

        return $prefix;
    }
    /**
     * @var \AppBundle\Entity\Country
     */
    private $country;


    /**
     * Set country
     *
     * @param \AppBundle\Entity\Country $country
     * @return Staff
     */
    public function setCountry(\AppBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \AppBundle\Entity\Country 
     */
    public function getCountry()
    {
        return $this->country;
    }
}
