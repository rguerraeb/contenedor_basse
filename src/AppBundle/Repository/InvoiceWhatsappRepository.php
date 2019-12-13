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
		
		$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		$res->execute ();
		return $res->fetchAll();
	}

	
}
