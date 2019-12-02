<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class UserRepository extends EntityRepository implements UserLoaderInterface
{
	public function login ($user) {
		
		
		$query = "
			SELECT 
				ur.id as role_id, first_name, last_name, email, u.id as id
			FROM
			    user u, user_role ur
			WHERE status = 'ACTIVO'
			AND u.id = :userId
			AND ur.id = u.user_role_id;
		;
		";

		$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		$res->bindValue ( 'userId', $user->getId(), \PDO::PARAM_STR );
		
		$res->execute ();
		
		return $res->fetch ();
	}
	
	public function loginStaff($user)
	{
		$query = "
			SELECT
		    u.staff_id, u.name, u.phone_main, u.tax_identifier, u.email, u.citizen_id, u.gender, '2' as user_role_id, u.job_position_id 
		FROM
		    staff u
		WHERE
			u.staff_id = :userId
		;
		";			
		
		$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		$res->bindValue ( 'userId', $user->getStaffId(), \PDO::PARAM_STR );		
	
		$res->execute ();
		
		$staff = $res->fetch();
		
		// hacer el cambio de rol dependiendo del job position
		
		if ($staff["job_position_id"] == 1) {		    
		    $staff["user_role_id"] = 8;
		} elseif ($staff["job_position_id"] == 2)
			$staff["user_role_id"] = 9;			   
			
	    return $staff;
	
	}

	public function getModuleCatalog($userRoleId)
	{
		$query = "
				SELECT 
					urmp.id as urmp_id, urmp.view_module as viewm,
					urmp.read_permission as rp, urmp.write_permission as wp,
					urmp.edit_permission as ep, urmp.delete_permission as dp,
					urmp.main_module as mm, mc.name as module_name,
					mc.description as description, mc.url_access as url_access,
					mc.id as mcid, mc.module_icon,
					(SELECT COUNT(id) FROM module_catalog WHERE parent_id = mc.id) as count_children
				FROM
					user_role_module_permission urmp
				INNER JOIN
					module_catalog mc ON mc.id = urmp.module_id
				WHERE
					urmp.user_role_id = :userRoleId
				AND
					mc.visible = :visible
				ORDER BY
					mc.order_module ASC
		";
		
		$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		$res->bindValue ( "userRoleId", $userRoleId, \PDO::PARAM_INT );
		$res->bindValue ( "visible", 1, \PDO::PARAM_INT );
		
		$res->execute ();
		
		return $res->fetchAll ();
	}

	/**
	 * Get children for module
	 *
	 * @param string $parentId id of parent module
	 * @return array() query result
	 */
	public function getModuleChildren($parentId, $userRoleId){
		$query = "
			SELECT 
				urmp.id as urmp_id, urmp.view_module as viewm,
				urmp.read_permission as rp, urmp.write_permission as wp,
				urmp.edit_permission as ep, urmp.delete_permission as dp,
				urmp.main_module as mm, mc.name as module_name,
				mc.description as description, mc.url_access as url_access,
				mc.id as mcid, mc.module_icon
			FROM
				user_role_module_permission urmp
			INNER JOIN
				module_catalog mc ON mc.id = urmp.module_id
			WHERE
				urmp.user_role_id = :userRoleId
			AND
				mc.visible = :visible
			AND
				mc.parent_id = :parentId
			ORDER BY
					mc.order_module ASC
		";

		$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		$res->bindValue ( "userRoleId", $userRoleId, \PDO::PARAM_INT );
		$res->bindValue ( "visible", 1, \PDO::PARAM_INT );
		$res->bindValue ( "parentId", $parentId, \PDO::PARAM_INT );

		$res->execute ();

		return $res->fetchAll ();
	}

	public function loadUserByUsername($email) {
        return $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Gets Active list of users
     *
     * @return array() List of active users
     */
    public function getList(){
		$em = $this->getEntityManager();
		return $em->getRepository('AppBundle:User')->findAll();
	}
}
