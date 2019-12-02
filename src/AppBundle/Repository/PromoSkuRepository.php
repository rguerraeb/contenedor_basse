<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class PromoSkuRepository extends EntityRepository
{
    /**
     * Search in database with search parameters
     *
     * @param Promo $promo promo to use as filter
     * @return array results of query
     */
    public function getSkuByPromo($promo) {
        $em = $this->getEntityManager();

        $query = $this->createQueryBuilder('pjp')
            ->select('jp')
            ->from('AppBundle:sku', 'jp')
            ->where('jp = pjp.sku')
            ->andWhere('pjp.promo = :promo')
            ->setParameter('promo', $promo)
            ->groupBy('jp');

        return $query->getQuery()->getResult();
    }
}
