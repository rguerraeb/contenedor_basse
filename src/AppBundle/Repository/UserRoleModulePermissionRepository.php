<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class UserRoleModulePermissionRepository extends EntityRepository
{
	
	
	
	public function findOneByMd5Id($md5Id) {
		$query = "
			SELECT
		    u.id
		FROM
		    user_role_module_permission u
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
