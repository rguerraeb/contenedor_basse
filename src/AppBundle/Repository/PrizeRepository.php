<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Prize;
use AppBundle\Entity\PromoCupon;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class PrizeRepository extends EntityRepository
{
	
	
	Public function getPrize($staff_id,$country_id){
		//$date = date("Y-m-d");
		$date = "2019-12-04";
		$query = "SELECT p.prize_type FROM  prize as p
					INNER JOIN staff_code as s on s.prize_id = p.id
					WHERE s.staff_id = $staff_id
					AND p.prize_type = 'PREMIUM' 
					LIMIT 1"
					;
					
		$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		$res->execute ();		
		$row = $res->fetch();
		$extra_validation = "";



		if($row){
			$extra_validation = "AND p.prize_type != 'PREMIUM'";
		}
		
		$query = "SELECT p.*, (SUM(d.amount)-already_given)  available FROM  prize p  
			INNER JOIN  prize_distribution d ON d.prize_id = p.id
            WHERE
            p. already_given < p.amount 
			AND p.country_id = '$country_id'
			$extra_validation
            AND d.date_activate <= '$date'
            GROUP BY  d.prize_id 
            HAVING SUM(d.amount) > p.already_given
			ORDER BY (rand() * p.probability)  
			LIMIT 1 ";
			
			
		$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		$res->execute ();		
		$row = $res->fetch ();
		
		
		$query = "update prize set already_given = already_given+1 where id = $row[id]";
		$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		$res->execute ();
		
		return $row;	
	}
	
	
	public function getList( $telco = "" ){
		$em = $this->getEntityManager();
		$block_array = array();
				
		
		$prizes = $em->getRepository('AppBundle:Prize')->findBy(
			array(
				'isActive' => '1',
			)
			,array(
				'orderNumber' => 'ASC'
			)
		);
		$prize_array = array();
		$i = 0;
		foreach($prizes as $prize){
			$i++;			
			$query = "select count(*) as exchange from prize_exchange where prize_id = ".$prize->getId();
			$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
			$res->execute ();
			$exchange = $res->fetchAll ();
			$actual_quantity = $prize->getQuantity() - $exchange[0]["exchange"];
			if($actual_quantity > 0 && $prize->getQuantity() != ""){
				$prize_array[$i]["quantity"]=$prize->getQuantity() - $exchange[0]["exchange"];
				$prize_array[$i]["id"]=$prize->getId();
				$prize_array[$i]["name"]=$prize->getName();
				$prize_array[$i]["amount"]=$prize->getAmount();
				$prize_array[$i]["imagePath"]=$prize->getImagePath();
				$prize_array[$i]["displayName"]=$prize->getDisplayName();
				$prize_array[$i]["value"]=$prize->getValue();
			}elseif($prize->getQuantity() == ""){
				$prize_array[$i]["quantity"]='none';
				$prize_array[$i]["id"]=$prize->getId();
				$prize_array[$i]["name"]=$prize->getName();
				$prize_array[$i]["amount"]=$prize->getAmount();
				$prize_array[$i]["imagePath"]=$prize->getImagePath();
				$prize_array[$i]["displayName"]=$prize->getDisplayName();
				$prize_array[$i]["value"]=$prize->getValue();
				}
		}
		return $prize_array;
	}
	
	public function getValidCuponPromoUgc(){
		$query = "
			SELECT * 
			FROM 
				promo_cupon 	
			WHERE
				 promo_code_id >= 12625 and staff_id_used_by is null 
				 and code not in (select cupon from register_pending where cupon != '')
		;
		";

		$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		
		$res->execute ();
		
		return $res->fetch ();
	}
	
	public function getAlreadyUsedPromoUgc($dpi){
		$query = "
			SELECT * 
		FROM 
			promo_cupon as p
		INNER JOIN
			register_pending as r on r.cupon = p.code
		WHERE
			r.citizen_id = :dpi
		;
		";

		$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		$res->bindValue ( 'dpi', $dpi, \PDO::PARAM_STR );
		
		$res->execute ();
		
		return $res->fetchAll ();
	}
}
