<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class PromoPointOfSaleRepository extends EntityRepository
{
    /**
     * Search in database with search parameters
     *
     * @param Promo $promo promo to use as filter
     * @return array results of query
     */
    public function getPointOfSaleByPromo($promo) {
        $em = $this->getEntityManager();

        $query = $this->createQueryBuilder('ppos')
            ->select('pos')
            ->from('AppBundle:PointOfSale', 'pos')
            ->where('ppos.promo = :promo')
            ->setParameter('promo', $promo)
            ->andWhere('ppos.pointOfSale = pos')
            ->groupBy('pos');

        return $query->getQuery()->getResult();
    }
    
    
}
