<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class CampaignRepository extends EntityRepository
{
	
	public function findOneByMd5Id($md5Id) {
		$query = "
			SELECT
		    c.campaign_id
		FROM
			campaign c
		WHERE
		    md5(c.campaign_id) = :md5Id
		;
		";
		$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		$res->bindValue ( 'md5Id', $md5Id, \PDO::PARAM_STR );
		
		$res->execute ();
		
		return $res->fetch ();
	}

	public function getCampaignExist($name) {
		$query = "
			SELECT campaign_id FROM campaign WHERE name='$name';
		";
		$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		
		$res->execute ();
		
		return $res->fetch ();
	}

	public function getActiveCampaings() {
        $query = "SELECT campaign_id from campaign
				WHERE now() BETWEEN start_from AND end_at
				AND STATUS='ACTIVO'";
        
        $res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
        $res->execute();
        
        return $res->fetch(); //fetchAll
	}

	function generateSendCode ($campaignId, $staffId  )
	{       
		
		$url = "http://devus.ebfuture.net/bmaldonado/sherwin_williams_promos_2019/web/app_dev.php/ws/generate-code";
		$headers= array('Accept: application/json','Content-Type: application/json'); 
		$data = array(
			"campaignId" => $campaignId,
			"staffId" => $staffId
		);
	
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_POST, count($data));
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$output = curl_exec($ch); 
		
		curl_close($ch); 
		//var_dump($output); // show output
		//die;
		return $output;
	}

	
}
