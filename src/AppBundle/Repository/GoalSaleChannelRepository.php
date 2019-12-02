<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class GoalSaleChannelRepository extends EntityRepository
{
    /**
     * Search in database with search parameters
     *
     * @param Goal $goal goal to use as filter
     * @return array results of query
     */
    public function getSaleChannelByGoal($goal) {
        $em = $this->getEntityManager();

        $query = $this->createQueryBuilder('gsc')
            ->select('sc')
            ->from('AppBundle:SaleChannel', 'sc')
            ->where('sc = gsc.saleChannel')
            ->andWhere('gsc.goal = :goal')
            ->setParameter('goal', $goal)
            ->groupBy('sc');

        return $query->getQuery()->getResult();
    }
}
