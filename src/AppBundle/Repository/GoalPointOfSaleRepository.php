<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class GoalPointOfSaleRepository extends EntityRepository
{
    /**
     * Search in database with search parameters
     *
     * @param Goal $goal goal to use as filter
     * @return array results of query
     */
    public function getPointOfSaleByGoal($goal) {
        $em = $this->getEntityManager();

        $query = $this->createQueryBuilder('gpos')
            ->select('pos')
            ->from('AppBundle:PointOfSale', 'pos')
            ->where('gpos.goal = :goal')
            ->setParameter('goal', $goal)
            ->andWhere('gpos.pointOfSale = pos')
            ->groupBy('pos');

        return $query->getQuery()->getResult();
    }
}
