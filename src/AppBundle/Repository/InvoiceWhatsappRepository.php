<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class InvoiceWhatsappRepository extends EntityRepository
{
	
	public function getMessages() {
		$query = "	SELECT 	s.phone, p.message, sc.whatsapp_status, sc.staff_code_id, s.country_id as country, sc.code
			    	FROM 	staff_code sc, staff s, prize p 
			    	WHERE 	sc.whatsapp_status = 'pendiente' 
			    	AND 	sc.staff_id = s.staff_id 
			    	AND 	sc.prize_id = p.id ";
		$resApproved = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		$resApproved->execute ();

		$query = "	SELECT 	iw.invoice_id, iw.status, s.phone, iw.notifi_status, iw.rejection_message as message, s.country_id as country
					FROM 	invoice_whatsapp iw, staff s
					WHERE 	iw.status = 3
					AND 	iw.notifi_status = 'pendiente'
					AND 	iw.staff_id = s.staff_id ";
		$resRejected = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		$resRejected->execute ();

		$res = array('resApproved' => $resApproved->fetchAll(), 'resRejected' => $resRejected->fetchAll());
		return $res;
	}

	public function getInvProcessed() {
		$query = "	SELECT 	iw.invoice_id as invoiceId, s.phone, iw.created_at as createdAt, iw.updated_at as updatedAt, ms.name as statusName, iw.invoice_number as invoiceNumber, iw.recurrent
					FROM 	invoice_whatsapp iw, staff s, main_status ms
					WHERE	iw.status > 1
					AND 	iw.staff_id = s.staff_id
					AND 	iw.status = ms.main_status_id
					ORDER BY iw.updated_at DESC";
		$gip = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		$gip->execute ();
		$res = $gip->fetchAll();
		return $res;
	}

	public function getQtyCodes($iwId) {
		$query = "	SELECT 		COUNT(1) as qty_codes
					FROM 		invoice_whatsapp iw, staff s, staff_code sc
					WHERE 		iw.invoice_id = '$iwId' 
					AND 		iw.staff_id = s.staff_id
					AND 		s.staff_id = sc.staff_id";
		$gip = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		$gip->execute ();
		$res = $gip->fetch();
		return $res['qty_codes'];
	}

	
}
