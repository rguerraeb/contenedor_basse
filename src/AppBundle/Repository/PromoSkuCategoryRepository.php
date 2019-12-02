<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class PromoSkuCategoryRepository extends EntityRepository
{
    /**
     * Search in database with search parameters
     *
     * @param Promo $promo promo to use as filter
     * @return array results of query
     */
    public function getSkuCategoryByPromo($promo) {
        $em = $this->getEntityManager();

        $query = $this->createQueryBuilder('pjp')
            ->select('jp')
            ->from('AppBundle:SkuCategory', 'jp')
            ->where('jp = pjp.skuCategory')
            ->andWhere('pjp.promo = :promo')
            ->setParameter('promo', $promo)
            ->groupBy('jp');

        return $query->getQuery()->getResult();
    }
}
