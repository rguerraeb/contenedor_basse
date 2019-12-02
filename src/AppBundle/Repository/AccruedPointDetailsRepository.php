<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class AccruedPointDetailsRepository extends EntityRepository
{
    public function getAccruedPointsAvailable($staffId)
    {
        $query = "select 
        				available_points, accrued_point_details_id, sale_id, point_type_id  
					from 
						accrued_point_details where staff_id = $staffId and available_points > 0 
					order by 
						accrued_point_details_id asc ";
        $res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
        $res->execute ();
        return $res->fetchAll ();
    }
}