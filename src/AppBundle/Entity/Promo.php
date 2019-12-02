<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Promo
 */
class Promo
{
    /**
     * @var integer
     */
    private $promoId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $status;

    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var \DateTime
     */
    private $endDate;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \AppBundle\Entity\PromoCategory
     */
    private $promoCategory;

    private $promoPrizes;


    /**
     * Get promoId
     *
     * @return integer 
     */
    public function getPromoId()
    {
        return $this->promoId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Promo
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
     * Set status
     *
     * @param string $status
     * @return Promo
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Promo
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Promo
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Promo
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
     * @return Promo
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
     * Set promoCategory
     *
     * @param \AppBundle\Entity\PromoCategory $promoCategory
     * @return Promo
     */
    public function setPromoCategory(\AppBundle\Entity\PromoCategory $promoCategory = null)
    {
        $this->promoCategory = $promoCategory;

        return $this;
    }

    /**
     * Get promoCategory
     *
     * @return \AppBundle\Entity\PromoCategory 
     */
    public function getPromoCategory()
    {
        return $this->promoCategory;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->promoPrizes = new ArrayCollection();
    }

    public function getPromoPrizes(){
        return $this->promoPrizes;
    }

    public function setPromoPrizes($promoPrizes){
        $this->promoPrizes = $promoPrizes;

        return $this;
    }

    public function addPromoPrize(PromoPrize $promoPrize)
    {
        $promoPrize->setPromo($this);
        $this->promoPrizes->add($promoPrize);
    }

    public function removePromoPrize(PromoPrize $promoPrize)
    {
        $this->promoPrizes->removeElement($promoPrize);
    }

    /**
     * Function to tell if promo should just have on prize
     *
     * @return bollean if promo should just have one prize
     */
    public function isOnePrize() {
        if ($this->promoCategory) {
            $catId = $this->promoCategory->getPromoCategoryId();

            if ($catId == 8) {
                return false;
            }
        }

        return true;
    }

    /**
     * Gets the ids of valid prizes for the assigned promo category
     *
     * @return array ids of prize Types
     */
    private function getValidPrizeTypes() {
        if ($this->promoCategory) {
            $catId = $this->promoCategory->getPromoCategoryId();

            if ($catId == 2 || $catId == 3 || $catId == 4 || $catId == 5) {
                return array(
                    1, 2, 3
                );
            }
            else if ($catId == 1) {
                return array(
                    1, 2
                );
            }
            else if ($catId == 6 || $catId == 7 || $catId == 8) {
                return array(
                    3, 4
                );
            }
        }

        return array();
    }

    /**
     * Checks if the prize type that is going to be added is correct
     *
     * @param int $prizeTypeId id of prize type
     * @return boolean if the prize type for this promo and it's category is correct
     */
    public function isPrizeTypeCorrect($prizeTypeId) {
        $validIds = $this->getValidPrizeTypes();

        if (in_array($prizeTypeId, $validIds)) {
            return true;
        }

        return false;
    }

    /**
     * Says if promo uses the PromoPrize fields of:
     *  - probability
     *  - maxQuantity
     *  - notificationMessage
     * 
     * @return boolean if it uses them or not
     */
    public function usesPrMqNm() {
        if ($this->promoCategory) {
            $catId = $this->promoCategory->getPromoCategoryId();

            if ($catId == 8) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if promo has finished, based on current date
     *
     * @return boolean if promo finished or not
     */
    public function alreadyFinished() {
        return new \DateTime() > $this->endDate;
    }
}
