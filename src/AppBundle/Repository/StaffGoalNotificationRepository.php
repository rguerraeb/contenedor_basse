<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Connection;

class StaffGoalNotificationRepository extends EntityRepository
{
    /**
     * Finds StaffGoalNotification with the same staff, goal but a higher or equal percentage
     *
     * @param float percentage percentage of notification, looking for a higher or equal percentage
     * @param Staff $staff staff column
     * @param Goal $goal goal column
     */
    public function findHigherPercentage($percentage, $staff, $goal) {
        $em = $this->getEntityManager();

        $repo = $em->getRepository('AppBundle:StaffGoalNotification');
        $query = $repo->createQueryBuilder('sgn');
        $query
            ->innerJoin('sgn.notification', 'n')
            ->where('n.percentage >= :percentage')
            ->andWhere('sgn.staff = :staff')
            ->andWhere('sgn.goal = :goal')
            ->setParameter('percentage', $percentage)
            ->setParameter('staff', $staff)
            ->setParameter('goal', $goal)
        ;

        return $query->getQuery()->getResult();
    }
}


