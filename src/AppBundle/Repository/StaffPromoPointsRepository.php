<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\StaffPromoPoints;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class StaffPromoPointsRepository extends EntityRepository
{
    public function getStaffPromoPoints($staffId)
    {
        $query = "select sum(points) as points from staff_promo_points where staff_id = $staffId";
        $res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
        $res->execute ();
        return $res->fetchAll ();
    }
}