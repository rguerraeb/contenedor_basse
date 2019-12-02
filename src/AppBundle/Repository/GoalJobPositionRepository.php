<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class GoalJobPositionRepository extends EntityRepository
{
    /**
     * Search in database with search parameters
     *
     * @param Goal $goal goal to use as filter
     * @return array results of query
     */
    public function getJobPositionByGoal($goal) {
        $em = $this->getEntityManager();

        $query = $this->createQueryBuilder('gjp')
            ->select('jp')
            ->from('AppBundle:JobPosition', 'jp')
            ->where('jp = gjp.jobPosition')
            ->andWhere('gjp.goal = :goal')
            ->setParameter('goal', $goal)
            ->groupBy('jp');

        return $query->getQuery()->getResult();
    }
}
