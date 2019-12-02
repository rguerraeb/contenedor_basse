<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class PromotionRepository extends EntityRepository
{
	
	public function findOneByMd5Id($md5Id) {
		$query = "
			SELECT
		    p.promotion_id
		FROM
			promotion p
		WHERE
		    md5(p.promotion_id) = :md5Id
		;
		";
		$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		$res->bindValue ( 'md5Id', $md5Id, \PDO::PARAM_STR );
		
		$res->execute ();
		
		return $res->fetch ();
	}

	public function getPromotionExist($name, $promoCode, $campaign) {
		$query = "
			SELECT promotion_id FROM promotion WHERE name='$name' AND campaign_id=$campaign OR promo_code=$promoCode AND campaign_id=$campaign;
		";
		$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		
		$res->execute ();
		
		return $res->fetch ();
	}

	
}
