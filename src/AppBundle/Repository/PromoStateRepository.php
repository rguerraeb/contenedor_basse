<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class PromoStateRepository extends EntityRepository
{
    /**
     * Search in database with search parameters
     *
     * @param Promo $promo promo to use as filter
     * @return array results of query
     */
    public function getStateByPromo($promo) {
        $em = $this->getEntityManager();

        $query = $this->createQueryBuilder('ps')
            ->select('s')
            ->from('AppBundle:State', 's')
            ->where('s = ps.state')
            ->andWhere('ps.promo = :promo')
            ->setParameter('promo', $promo)
            ->groupBy('s');

        return $query->getQuery()->getResult();
    }
}
