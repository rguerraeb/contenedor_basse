<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class PromoCityRepository extends EntityRepository
{
    /**
     * Search in database with search parameters
     *
     * @param Promo $promo promo to use as filter
     * @return array results of query
     */
    public function getCityByPromo($promo) {
        $em = $this->getEntityManager();

        $query = $this->createQueryBuilder('pc')
            ->select('c')
            ->from('AppBundle:City', 'c')
            ->where('c = pc.city')
            ->andWhere('pc.promo = :promo')
            ->setParameter('promo', $promo)
            ->groupBy('c');

        return $query->getQuery()->getResult();
    }
}