<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SiteContent
 */
class SiteContent
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $url;
   
    private $image;

    /**
     * @var string
     */
    private $keywords;

    /**
     * @var integer
     */
    private $published;

    /**
     * @var float
     */
    private $orderContent;

    /**
     * @var boolean
     */
    private $isNews;

    /**
     * @var \DateTime
     */
    private $publicationDate;

    /**
     * @var boolean
     */
    private $showContactForm;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \AppBundle\Entity\Country
     */
    private $country;

    /**
     * Unmapped attribute
     *
     * @var string
     */
    /**
     * Unmapped attribute
     *
     * @var string
     */
    /**
     * @Assert\Image(
     *     maxWidth = 800,
     *     maxWidthMessage = "La imagen debe tener un ancho menor a 800 píxeles",
     *     mimeTypesMessage = "Por favor elija una imagen"
     * )
     */
    private $imageFile;
    
    /*
     * @var string
     */
    private $contentType;
    
    /*
     * @var integer
     */
    private $duration;

    /**
     * @return integer
     */
    public function getDuration ()
    {
        return $this->duration;
    }

    /**
     * @param integer $duration
     */
    public function setDuration ($duration)
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContentType ()
    {
        return $this->contentType;
    }

    /**
     * @param mixed $contentType
     */
    public function setContentType ($contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }

    /**
     * Set imageFile
     *
     * @param string $imageFile
     * @return SiteContent
     */
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;

        return $this;
    }

    /**
     * Get imageFile
     *
     * @return string 
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

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
     * Set title
     *
     * @param string $title
     * @return SiteContent
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return SiteContent
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
     * Set content
     *
     * @param string $content
     * @return SiteContent
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return SiteContent
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return SiteContent
     */
    public function setImage($image)
    {
        $this->image = $image;
    
        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set keywords
     *
     * @param string $keywords
     * @return SiteContent
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    
        return $this;
    }

    /**
     * Get keywords
     *
     * @return string 
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set published
     *
     * @param integer $published
     * @return SiteContent
     */
    public function setPublished($published)
    {
        $this->published = $published;
    
        return $this;
    }

    /**
     * Get published
     *
     * @return integer 
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set orderContent
     *
     * @param float $orderContent
     * @return SiteContent
     */
    public function setOrderContent($orderContent)
    {
        $this->orderContent = $orderContent;
    
        return $this;
    }

    /**
     * Get orderContent
     *
     * @return float 
     */
    public function getOrderContent()
    {
        return $this->orderContent;
    }

    /**
     * Set isNews
     *
     * @param boolean $isNews
     * @return SiteContent
     */
    public function setIsNews($isNews)
    {
        $this->isNews = $isNews;
    
        return $this;
    }

    /**
     * Get isNews
     *
     * @return boolean 
     */
    public function getIsNews()
    {
        return $this->isNews;
    }

    /**
     * Set publicationDate
     *
     * @param \DateTime $publicationDate
     * @return SiteContent
     */
    public function setPublicationDate($publicationDate)
    {
        $this->publicationDate = $publicationDate;
    
        return $this;
    }

    /**
     * Get publicationDate
     *
     * @return \DateTime 
     */
    public function getPublicationDate()
    {
        return $this->publicationDate;
    }

    /**
     * Set showContactForm
     *
     * @param boolean $showContactForm
     * @return SiteContent
     */
    public function setShowContactForm($showContactForm)
    {
        $this->showContactForm = $showContactForm;
    
        return $this;
    }

    /**
     * Get showContactForm
     *
     * @return boolean 
     */
    public function getShowContactForm()
    {
        return $this->showContactForm;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return SiteContent
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
     * @return SiteContent
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
     * Set country
     *
     * @param \AppBundle\Entity\Country $country
     * @return SiteContent
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
    /**
     * @var \AppBundle\Entity\JobPosition
     */
    private $siteContentJobPosition;

    /**
     * @var \AppBundle\Entity\PointOfSale
     */
    private $siteContentPos;


    /**
     * Set siteContentJobPosition
     *
     * @param \AppBundle\Entity\JobPosition $siteContentJobPosition
     * @return SiteContent
     */
    public function setSiteContentJobPosition(\AppBundle\Entity\JobPosition $siteContentJobPosition = null)
    {
        $this->siteContentJobPosition = $siteContentJobPosition;

        return $this;
    }

    /**
     * Get siteContentJobPosition
     *
     * @return \AppBundle\Entity\JobPosition 
     */
    public function getSiteContentJobPosition()
    {
        return $this->siteContentJobPosition;
    }

    /**
     * Set siteContentPos
     *
     * @param \AppBundle\Entity\PointOfSale $siteContentPos
     * @return SiteContent
     */
    public function setSiteContentPos(\AppBundle\Entity\PointOfSale $siteContentPos = null)
    {
        $this->siteContentPos = $siteContentPos;

        return $this;
    }

    /**
     * Get siteContentPos
     *
     * @return \AppBundle\Entity\PointOfSale 
     */
    public function getSiteContentPos()
    {
        return $this->siteContentPos;
    }
    /**
     * @var integer
     */
    private $countryId;


    /**
     * Set countryId
     *
     * @param integer $countryId
     * @return SiteContent
     */
    public function setCountryId($countryId)
    {
        $this->countryId = $countryId;

        return $this;
    }

    /**
     * Get countryId
     *
     * @return integer 
     */
    public function getCountryId()
    {
        return $this->countryId;
    }
    /**
     * @var \AppBundle\Entity\User
     */
    private $createdBy;

    /**
     * @var \AppBundle\Entity\User
     */
    private $updatedBy;


    /**
     * Set createdBy
     *
     * @param \AppBundle\Entity\User $createdBy
     * @return SiteContent
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
     * @return SiteContent
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
}
