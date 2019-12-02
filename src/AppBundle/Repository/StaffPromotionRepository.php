<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Connection;

class StaffPromotionRepository extends EntityRepository
{

  public function findOneByMd5Id($md5Id) {
		$query = "
			SELECT
		    p.staff_promotion_id
		FROM
			staff_promotion p
		WHERE
		    md5(p.staff_promotion_id) = :md5Id
		;
		";
		$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		$res->bindValue ( 'md5Id', $md5Id, \PDO::PARAM_STR );
		
		$res->execute ();
		
		return $res->fetch ();
	}
	
	public function getAllStaffPromotions($distibutorId) {
        $query = "SELECT * FROM staff_promotion
				WHERE distributor_id= $distibutorId;
				ORDER BY staff_promotion_id DESC";
        
        $res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
        $res->execute();
        
        return $res->fetchAll();
	}

	public function getReportCanjes($distibutorId) {
		//if ($distibutorId == 'ADMIN'){
			$query = "SELECT sp.staff_promotion_id, pz.name as prize_name,  sp.code_id, count(sp.promotion_id) qty_promos, sp.campaign_id, sp.staff_id, sp.distributor_id, sp.quantity_taken, sp.invoice_number, sp.invoice_img, sp.created_at fechaCanje,
					sc.code, s.name participant, s.citizen_id, s.phone, c.name campaignName, d.name distributorName, sc.staff_code_id , sc.created_at fechaEnvio, sc.responsable, sc.store
					FROM staff_promotion sp
					LEFT JOIN staff_code sc on sc.staff_code_id = sp.code_id
					LEFT JOIN staff s on s.staff_id = sp.staff_id
					LEFT JOIN campaign c on c.campaign_id = sp.campaign_id
					LEFT JOIN prize pz on sc.prize_id = pz.id
					LEFT JOIN distributor d on d.distributor_id = sp.distributor_id
					GROUP BY sp.code_id
					ORDER BY sp.staff_promotion_id desc ;";
		/*}else{
			$query = "SELECT sp.staff_promotion_id, pz.name as prize_name, sp.code_id, count(sp.promotion_id) qty_promos, sp.campaign_id, sp.staff_id, sp.distributor_id, sp.quantity_taken, sp.invoice_number, sp.invoice_img, sp.created_at fechaCanje,
					sc.code, s.name participant, s.citizen_id, s.phone, c.name campaignName, d.name distributorName, sc.staff_code_id , sc.created_at fechaEnvio, sc.responsable, sc.store
					FROM staff_promotion sp
					
					LEFT JOIN staff_code sc on sc.staff_code_id = sp.code_id
					LEFT JOIN staff s on s.staff_id = sp.staff_id
					LEFT JOIN campaign c on c.campaign_id = sp.campaign_id
					LEFT JOIN distributor d on d.distributor_id = sp.distributor_id
					LEFT JOIN prize pz on sc.prize_id = pz.id
					WHERE  sp.distributor_id= $distibutorId
					GROUP BY sp.code_id
					ORDER BY sp.staff_promotion_id desc ;";
		}*/
        
        $res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
        $res->execute();
        
        return $res->fetchAll();
	}
}
