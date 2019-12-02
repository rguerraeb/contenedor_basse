<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class GoalRepository extends EntityRepository
{
    /**
     * Search in database with search parameters
     *
     * @param string $name LIKE parameter for name column
     * @param integer $typeId id of type of goal
     * @param integer $jobPositionId id of jobPosition
     * @return DoctrineQuery query as doctrine object
     */
    public function search($name, $typeId, $jobPositionId) {
        $em = $this->getEntityManager();

        $repo = $em->getRepository('AppBundle:Goal');
        $query = $repo->createQueryBuilder('g');

        // Add parameters if necesary
        if ($name !== NULL && $name != '') {
            $query->andWhere('g.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }

        // Add parameters if necesary
        if ($typeId !== NULL && $typeId != '') {
            $query->andWhere('g.goalType = :typeId')
                ->setParameter('typeId', $typeId);
        }

        // Add parameters if necesary
        if ($jobPositionId !== NULL && $jobPositionId != '') {
            $query->andWhere('g.jobPosition = :jobPositionId')
                ->setParameter('jobPositionId', $jobPositionId);
        }

        // Don't show eliminated ones
        $query->andWhere('g.goalStatus != 3');

        $query->orderBy('g.createdAt', 'DESC');

        $query->getQuery();

        return $query;
    }

    /**
     * Deactivates all active goals except one
     *
     * @param Goal $except the goal that is going to stay active
     */
    public function deactive($except) {
        $em = $this->getEntityManager();

        $query = $em->createQueryBuilder();

        // Update field
        $query
            ->update('AppBundle:Goal', 'g')
            ->set('g.goalStatus', 2)
        ;

        // Add parameters if necesary
        if ($except !== NULL) {
            $query->where('g != :except')
                ->setParameter('except', $except);
        }

        // Don't show eliminated ones
        $query->andWhere('g.goalStatus = 1');

        $query->getQuery()->execute();
    }

    /**
     * Looks for goals that intersect with an interval and jobPosition
     *
     * @param DateTime $start datetime object with start of interval
     * @param DateTime $end datetime object with end of interval
     * @param array $jobPositions jobPositions to filter
     * @param Goal $except exclude this goal
     */
    public function intersects($start, $end, $jobPositions, $except) {
        $em = $this->getEntityManager();

        $repo = $em->getRepository('AppBundle:Goal');
        $query = $repo->createQueryBuilder('g');

        $query
            ->where($query->expr()->between(
                ':start',
                'g.start',
                'g.end'
            ))
            ->orWhere($query->expr()->between(
                'g.start',
                ':start',
                ':end'
            ))
            ->setParameters(
                array(
                    'start' => $start,
                    'end' => $end
                )
            )
            ->andWhere(
                'g.goalStatus = 1'
            )
        ;

        if ($jobPositions && sizeof($jobPositions) > 0) {
            // Job positions filter
            $query
                ->from('AppBundle:GoalJobPosition', 'gjp')
                ->andWhere('gjp.goal = g');

            // Add each jobPosition as and (or (...)) in query
            $jpOrs = array();
            foreach ($jobPositions as $jobPosition) {
                // Add to ors array
                $jpOrs[] = 'gjp.jobPosition = ' . $jobPosition->getId();
            }

            // Add to query
            $query->andWhere(join(' OR ', $jpOrs));
        }

        if ($except) {
            $query
                ->andWhere(
                    'g != :goal'
                )
                ->setParameter('goal', $except)
            ;
        }

        return $query->getQuery()->getResult();
    }

    /**
     * Calculates the average of a jobPosition in a quantity of months
     *
     * @param integer $months how many months ago starts the search
     * @param array $jobPositionIds filter by jobPosition
     * @return float average of sales. -1 if error
     */
    public function average($months, $jobPositionIds) {
        // See if error
        if (!$months || $months < 0) {
            return 0;
        }

        $em = $this->getEntityManager();
        $now = new \DateTime();

        // Get start date
        $intervalS = new \DateInterval('P' . $months . 'M');
        $goalStart = clone $now;
        $goalStart->sub($intervalS);

        $staffs = $em->getRepository('AppBundle:Staff')
            ->findByJobPositions($jobPositionIds);

        $total = sizeof($staffs);
        $sum = 0;

        foreach ($staffs as $staff) {
            if ($staff->isSeller()) {
                // Get total of sales
                $contSales = $em->getRepository('AppBundle:Sale')
                    ->count(
                        $staff,
                        $goalStart->format('Y-m-d 00:00:00'),
                        $now->format('Y-m-d 23:59:59')
                    );
            }
            else {
                $contSales = $em->getRepository('AppBundle:Sale')
                    ->countHierarchy(
                        $staff,
                        $goalStart->format('Y-m-01 00:00:00'),
                        $now->format('Y-m-t 23:59:59')
                    );
            }

            $sum += $contSales;
        }

        $average = 0;
        if ($total > 0) {
            $average = ceil($sum/$total);
        }

        return $average;
    }
}
