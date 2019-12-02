<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\StaffCode;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class StaffCodeRepository extends EntityRepository
{
    public function validateUniqueCode($code)
    {
        $query = "select count(*) as existing_number from staff_code where code = '$code'";
        $res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
        $res->execute ();
        
        $result = $res->fetchAll();
        
        return $result[0]["existing_number"];
    }    
    
    public function findOneByMd5Id($md5Id) {
		$query = "
			SELECT
		    sc.staff_code_id
		FROM
			staff_code sc
		WHERE
		    md5(sc.staff_code_id) = :md5Id
		;
		";
		$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		$res->bindValue ( 'md5Id', $md5Id, \PDO::PARAM_STR );
		
		$res->execute ();
		
		return $res->fetch ();
	}
	
	public function getTotalParticipants() {
		$query = "SELECT count(DISTINCT(staff_id)) participants FROM staff_code;";
		$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		
		$res->execute ();
		
		return $res->fetch ();
	}
	
	
}
