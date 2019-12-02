<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class DistributorRepository extends EntityRepository
{
	
	public function findOneByMd5Id($md5Id) {
		$query = "
			SELECT
		    d.distributor_id
		FROM
			distributor d
		WHERE
		    md5(d.distributor_id) = :md5Id
		;
		";
		$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		$res->bindValue ( 'md5Id', $md5Id, \PDO::PARAM_STR );
		
		$res->execute ();
		
		return $res->fetch ();
	}

	public function getDistributorExist($name) {
		$query = "
			SELECT distributor_id FROM distributor WHERE name='$name';
		";
		$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		
		$res->execute ();
		
		return $res->fetch ();
	}

	
}
