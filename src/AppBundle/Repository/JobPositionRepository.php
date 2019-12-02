<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Connection;

class JobPositionRepository extends EntityRepository
{
    /**
     * Finds jobPositions using != for every parameter
     *
     * @param array $parameters values to use != in query. Expected arrays with field, value
     * @return array() JobPosition where != parameters
     */
    public function findByNot($parameters) {
        $em = $this->getEntityManager();

        $qb = $this->createQueryBuilder('jp');

        $cont = 0;
        foreach ($parameters as $parameter) {
            $field = $parameter['field'];
            $value = $parameter['value'];
            // Apply parameters
            $qb->andWhere(
                $qb->expr()->not(
                    $qb->expr()->eq(
                        'jp.' . $field,
                        '?' . $cont
                    )
                )
            );
            $qb->setParameter($cont, $value);

            $cont++;
        }

        return $qb->getQuery()
            ->getResult();
    }
}


