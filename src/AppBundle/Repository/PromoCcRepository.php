<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class PromoCcRepository extends EntityRepository
{
    /**
     * Search in database with search parameters
     *
     * @param Promo $promo promo to use as filter
     * @return array results of query
     */
    public function getSkuByPromo($promo) {
        $em = $this->getEntityManager();

        $query = $this->createQueryBuilder('pc')
            ->select('s')
            ->from('AppBundle:Sku', 's')
            ->where('s.cc = pc.cc')
            ->andWhere('pc.promo = :promo')
            ->setParameter('promo', $promo)
            ->groupBy('s');

        return $query->getQuery()->getResult();
    }
}
