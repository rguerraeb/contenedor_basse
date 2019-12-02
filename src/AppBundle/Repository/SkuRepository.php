<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class SkuRepository extends EntityRepository
{
    /**
     * Search in database with search parameters
     *
     * @return array All posible ccs for skus results of query
     */
    public function getCc() {
        $em = $this->getEntityManager();

        $query = $this->createQueryBuilder('s')
            ->select('s.cc')
            ->groupBy('s.cc');

        return $query->getQuery()->getResult();
    }
}