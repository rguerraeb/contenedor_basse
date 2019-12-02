<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Connection;

class JobPositionParentRepository extends EntityRepository
{
    /**
     * Finds a lower job position, a job position which has him as parent
     *
     * @param array $jobPositionIds ids of parent job position
     * @return array() JobPosition with parent_id $jobPositionId
     */
    public function findLower($jobPositionIds) {
        $em = $this->getEntityManager();

        $query = "
            SELECT
                child_id
            FROM
                job_position_parent
            WHERE
                parent_id IN (?)
        ";

        // Values to bind to query
        $values = array(
            $jobPositionIds
        );

        // Values types
        $types = array(
            Connection::PARAM_INT_ARRAY
        );

        $conn = $em->getConnection ();
        $stmt = $conn->executeQuery($query, $values, $types);

        return $stmt->fetchAll();
    }
}


