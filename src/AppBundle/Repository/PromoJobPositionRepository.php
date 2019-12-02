<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class PromoJobPositionRepository extends EntityRepository
{
    /**
     * Search in database with search parameters
     *
     * @param Promo $promo promo to use as filter
     * @return array results of query
     */
    public function getJobPositionByPromo($promo) {
        $em = $this->getEntityManager();

        $query = $this->createQueryBuilder('pjp')
            ->select('jp')
            ->from('AppBundle:JobPosition', 'jp')
            ->where('jp = pjp.jobPosition')
            ->andWhere('pjp.promo = :promo')
            ->setParameter('promo', $promo)
            ->groupBy('jp');

        return $query->getQuery()->getResult();
    }
}
