<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class PromoSaleChannelRepository extends EntityRepository
{
    /**
     * Search in database with search parameters
     *
     * @param Promo $promo promo to use as filter
     * @return array results of query
     */
    public function getSaleChannelByPromo($promo) {
        $em = $this->getEntityManager();

        $query = $this->createQueryBuilder('psc')
            ->select('sc')
            ->from('AppBundle:SaleChannel', 'sc')
            ->where('sc = psc.saleChannel')
            ->andWhere('psc.promo = :promo')
            ->setParameter('promo', $promo)
            ->groupBy('sc');

        return $query->getQuery()->getResult();
    }
}
