<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WhatsappImages
 */
class WhatsappImages
{
    /**
     * @var integer
     */
    private $whatsappImagesId;

    /**
     * @var string
     */
    private $imageName;

    /**
     * @var string
     */
    private $contactPhone;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var integer
     */
    private $status;


    /**
     * Get whatsappImagesId
     *
     * @return integer 
     */
    public function getWhatsappImagesId()
    {
        return $this->whatsappImagesId;
    }

    /**
     * Set imageName
     *
     * @param string $imageName
     * @return WhatsappImages
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get imageName
     *
     * @return string 
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * Set contactPhone
     *
     * @param string $contactPhone
     * @return WhatsappImages
     */
    public function setContactPhone($contactPhone)
    {
        $this->contactPhone = $contactPhone;

        return $this;
    }

    /**
     * Get contactPhone
     *
     * @return string 
     */
    public function getContactPhone()
    {
        return $this->contactPhone;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return WhatsappImages
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
     * Set status
     *
     * @param integer $status
     * @return WhatsappImages
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }
}
