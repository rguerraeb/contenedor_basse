<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\StaffPoints;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class StaffPointsRepository extends EntityRepository
{
	
	public function getClientAllCredits($staff_id)
	{
		$query = "select sum(point) as credits from staff_points where staff_id = $staff_id";
		$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		$res->execute ();
		return $res->fetchAll ();
	}

	
}


