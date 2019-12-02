<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class GoalCityRepository extends EntityRepository
{
    /**
     * Search in database with search parameters
     *
     * @param Goal $goal goal to use as filter
     * @return array results of query
     */
    public function getCityByGoal($goal) {
        $em = $this->getEntityManager();

        $query = $this->createQueryBuilder('gc')
            ->select('c')
            ->from('AppBundle:City', 'c')
            ->where('c = gc.city')
            ->andWhere('gc.goal = :goal')
            ->setParameter('goal', $goal)
            ->groupBy('c');

        return $query->getQuery()->getResult();
    }
}