<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class GoalStateRepository extends EntityRepository
{
    /**
     * Search in database with search parameters
     *
     * @param Goal $goal goal to use as filter
     * @return array results of query
     */
    public function getStateByGoal($goal) {
        $em = $this->getEntityManager();

        $query = $this->createQueryBuilder('gs')
            ->select('s')
            ->from('AppBundle:State', 's')
            ->where('s = gs.state')
            ->andWhere('gs.goal = :goal')
            ->setParameter('goal', $goal)
            ->groupBy('s');

        return $query->getQuery()->getResult();
    }
}
