<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class PromoRepository extends EntityRepository
{
    /**
     * Looks by a status that is not $status
     *
     * @param string $status status to exclude
     * @return array Promo promos without that status
     */
    public function findByNotStatus($status) {
        $query  = $this
            ->createQueryBuilder('p')
            ->where('p.status != :status')
            ->setParameter('status', $status)
        ;

        return $query->getQuery()->getResult();
    }

    /**
     * Looks for active promos that ended in an interval
     *
     * @param string $start start date of interval
     * @param string $end end date of interval
     * @return array Promos who ended in interval
     */
    public function findByEndDateBetween($start, $end) {
        $query = $this->createQueryBuilder('p');

        $query
            ->where($query->expr()->between(
                'p.endDate',
                ':start',
                ':end'
            ))
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->andWhere("p.status = 'ACTIVE'")
        ;

        return $query->getQuery()->getResult();
    }
    
    
    public function findActivePromos() {
        $query = $this->createQueryBuilder('p');
        
        $query
        ->where($query->expr()->between(
                ':today',
                'p.startDate',
                'p.endDate'
                ))
                ->setParameter('today', date('Y-m-d h:i:s'))                
                ->andWhere("p.status = 'ACTIVE'")
                ;
                
                return $query->getQuery()->getResult();
    }
    
    public function findValidStateforPointOfSale($pointOfSale, $promoStates) {
        foreach ($promoStates as $ps) {
            if ($ps->getState()->getStateId() == $pointOfSale->getState()->getStateId())
                return true;
        }
        return false;
    }
    
    public function findValidCityforPointOfSale($pointOfSale, $promoCities) {
        foreach ($promoCities as $pc) {
            if ($pc->getCity()->getId() == $pointOfSale->getCity()->getId())
                return true;
        }
        return false;
    }
    
    public function findValidPointOfSaleforPointOfSale($pointOfSale, $promoPointOfSales) {
        foreach ($promoPointOfSales as $pps) {
            if ($pps->getPointOfSale()->getPointOfSaleId() == $pointOfSale->getPointOfSaleId())
                return true;
        }
        return false;
    }
    
    public function findValidJobPosition($jobPosition, $promoJobPositions) {
        foreach ($promoJobPositions as $item) {
            if ($item->getJobPosition()->getId() == $jobPosition->getId())
                return true;
        }
        return false;
    }
    
    public function findValidSkuCategory($skuCategoryId, $promoSkuCategory) {
        foreach ($promoSkuCategory as $item) {
            if ($item->getSkuCategory()->getSkuCategoryId() == $skuCategoryId)
                return true;
        }
        return false;
    }
    
    public function findValidSku($skuId, $promoSku) {
        foreach ($promoSku as $item) {
            if ($item->getSku()->getSkuId() == $skuId)
                return true;
        }
        return false;
    }
}
