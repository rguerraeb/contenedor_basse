<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class InvoiceWhatsappRepository extends EntityRepository
{
	
	public function getMessages() {
		$query = "	SELECT 	s.phone, p.message, sc.whatsapp_status, sc.staff_code_id 
			    	FROM 	staff_code sc, staff s, prize p 
			    	WHERE 	sc.whatsapp_status = 'pendiente' 
			    	AND 	sc.staff_id = s.staff_id 
			    	AND 	sc.prize_id = p.id ";
		
		$resApproved = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		$resApproved->execute ();

		$query = "	SELECT 	iw.invoice_id, iw.status, s.phone, iw.notifi_status, iw.rejection_message as message
					FROM 	invoice_whatsapp iw, staff s
					WHERE 	iw.status = 3
					AND 	iw.staff_id = s.staff_id ";
		
		$resRejected = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		$resRejected->execute ();

		$res = array('resApproved' => $resApproved->fetchAll(), 'resRejected' => $resRejected->fetchAll());

		return $res;
	}

	
}
