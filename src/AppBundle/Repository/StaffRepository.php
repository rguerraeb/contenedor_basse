<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Connection;

class StaffRepository extends EntityRepository
{
	
	public function getAllCampaingPromotions() {
        $query = "SELECT * FROM promotion
			WHERE status = 'ACTIVO'
			AND campaign_id IN (
				SELECT campaign_id from campaign
				WHERE now() BETWEEN start_from AND end_at
				AND STATUS='ACTIVO')
				ORDER BY promotion_id DESC";
        
        $res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
        $res->execute();
        
        return $res->fetchAll();
	}
	
	public function findByMd5Id($md5) {
	    $query = "SELECT * FROM staff
			WHERE md5(staff_id) = '$md5'";
	    
	    $res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
	    $res->execute();
	    
	    return $res->fetch();
	}
}
