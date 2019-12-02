<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class ModuleCatalogRepository extends EntityRepository
{
	
	public function getList() {
		// obtener lugares desde
		$repository = $this->getEntityManager ()->getRepository ( 'AppBundle:ModuleCatalog' );
		$query = $repository->createQueryBuilder ( 'u' )
		->select ( "u.id, u.name, u.description, u.urlAccess, u.orderModule, u.visible" )		
		->orderBy("u.orderModule", "ASC")
		;
	
		return  $query->getQuery();
	}

	
	public function findOneByMd5Id($md5Id) {
		$query = "
			SELECT
		    u.id
		FROM
		    module_catalog u
		WHERE
		    md5(u.id) = :md5Id
		;
		";
		$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		$res->bindValue ( 'md5Id', $md5Id, \PDO::PARAM_STR );
		
		$res->execute ();
		
		return $res->fetch ();
	}

	
}
