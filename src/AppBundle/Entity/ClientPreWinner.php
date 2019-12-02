<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClientPreWinner
 */
class ClientPreWinner
{
    /**
     * @var integer
     */
    private $clientPreWinnerId;

    /**
     * @var string
     */
    private $clientPhone;

    /**
     * @var string
     */
    private $clientName;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \AppBundle\Entity\Promo
     */
    private $promo;

    /**
     * @var \AppBundle\Entity\ClientPreWinnerStatus
     */
    private $clientPreWinnerStatus;

    /**
     * @var \AppBundle\Entity\Sale
     */
    private $sale;


    /**
     * Get clientPreWinnerId
     *
     * @return integer 
     */
    public function getClientPreWinnerId()
    {
        return $this->clientPreWinnerId;
    }

    /**
     * Set clientPreWinnerId
     *
     * @return ClientPrewinner
     */
    public function setClientPreWinnerId($clientPreWinnerId)
    {
        $this->clientPreWinnerId = $clientPreWinnerId;

        return $this;
    }

    /**
     * Set clientPhone
     *
     * @param string $clientPhone
     * @return ClientPreWinner
     */
    public function setClientPhone($clientPhone)
    {
        $this->clientPhone = $clientPhone;

        return $this;
    }

    /**
     * Get clientPhone
     *
     * @return string 
     */
    public function getClientPhone()
    {
        return $this->clientPhone;
    }

    /**
     * Set clientName
     *
     * @param string $clientName
     * @return ClientPreWinner
     */
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;

        return $this;
    }

    /**
     * Get clientName
     *
     * @return string 
     */
    public function getClientName()
    {
        return $this->clientName;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return ClientPreWinner
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
     * Set promo
     *
     * @param \AppBundle\Entity\Promo $promo
     * @return ClientPreWinner
     */
    public function setPromo(\AppBundle\Entity\Promo $promo = null)
    {
        $this->promo = $promo;

        return $this;
    }

    /**
     * Get promo
     *
     * @return \AppBundle\Entity\Promo 
     */
    public function getPromo()
    {
        return $this->promo;
    }

    /**
     * Set clientPreWinnerStatus
     *
     * @param \AppBundle\Entity\ClientPreWinnerStatus $clientPreWinnerStatus
     * @return ClientPreWinner
     */
    public function setClientPreWinnerStatus(\AppBundle\Entity\ClientPreWinnerStatus $clientPreWinnerStatus = null)
    {
        $this->clientPreWinnerStatus = $clientPreWinnerStatus;

        return $this;
    }

    /**
     * Get clientPreWinnerStatus
     *
     * @return \AppBundle\Entity\ClientPreWinnerStatus 
     */
    public function getClientPreWinnerStatus()
    {
        return $this->clientPreWinnerStatus;
    }

    /**
     * Set sale
     *
     * @param \AppBundle\Entity\Sale $sale
     * @return ClientPreWinner
     */
    public function setSale(\AppBundle\Entity\Sale $sale = null)
    {
        $this->sale = $sale;

        return $this;
    }

    /**
     * Get sale
     *
     * @return \AppBundle\Entity\Sale 
     */
    public function getSale()
    {
        return $this->sale;
    }
}
