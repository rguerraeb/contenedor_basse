<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\PrizeExchange;
use AppBundle\Entity\AccruedPointDetails;
use AppBundle\Entity\RedeemPointsDetails;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use AppBundle\Entity\StaffRedeemPromoPoints;

class PrizeExchangeRepository extends EntityRepository
{
	
	public function getAllExchangeCredits($staff_id)
	{
		$query = "select sum(redeem_points) as credits from prize_exchange where staff_id = $staff_id";
		$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		$res->execute ();
		return $res->fetchAll ();
	}
	
	public function getAllExchangePromoPoints($staff_id)
	{
	    $query = "select sum(points) as credits from staff_redeem_promo_points where staff_id = $staff_id";
	    $res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
	    $res->execute ();
	    return $res->fetchAll ();
	}
	
	public function getAllPrizeExchange($prize_id)
	{
		$query = "select count(*) as exchange from prize_exchange where prize_id = $prize_id";
		$res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
		$res->execute ();
		return $res->fetchAll ();
	}
	

	public function redeemPrize($prize_id,$staff_id,$redeem_points,$channel_exchange, $capturePointsAvailable = 0)
	{
	    $em = $this->getEntityManager();
	    $staff = $em->getRepository('AppBundle:Staff')->findOneBy(array("staffId" => $staff_id));
	    $prize = $em->getRepository('AppBundle:Prize')->findOneBy(array("id" => $prize_id));	    	     
	    // guardar registro de canje de premio
	    $prizeExchange = new PrizeExchange();
	    $prizeExchange->setPrize($prize);
	    $prizeExchange->setStaff($staff);
	    $prizeExchange->setRedeemPoints($redeem_points);
	    $prizeExchange->setChannelExchange($channel_exchange);	    	   
	    $prizeExchange->setCreatedAt(new \DateTime());
	    $prizeExchange->setCapturePointsAvailable($capturePointsAvailable);
	    $em = $this->getEntityManager ();
	    $em->persist($prizeExchange);
	    $em->flush();
		
		$accruedPointDetails = $em->getRepository('AppBundle:AccruedPointDetails')->getAccruedPointsAvailable($staff_id);
		$points_nedeed = $redeem_points;
		foreach($accruedPointDetails as $info){
			$sale = $em->getRepository('AppBundle:Sale')->findOneBy(array("saleId" => $info["sale_id"]));
			$point_type = $em->getRepository('AppBundle:PointType')->findOneBy(array("pointTypeId" => $info["point_type_id"]));
			if($points_nedeed <= 0){
				break;
			}
			if($points_nedeed >= $info["available_points"]){
				$points_nedeed = $points_nedeed - $info["available_points"];
				$points_to_used = $info["available_points"];
				$redeem_point_detail = new RedeemPointsDetails();
				$redeem_point_detail->setSale($sale);	
				$redeem_point_detail->setPrizeExchange($prizeExchange);	
				$redeem_point_detail->setPoints($points_to_used);	
				$redeem_point_detail->setCreatedAt(new \DateTime());
				$redeem_point_detail->setStaffId($staff->getStaffId());	
				$redeem_point_detail->setPointType($point_type);	
				$em->persist($redeem_point_detail);
				$em->flush();
			}else{
				$points_to_used = $points_nedeed;
				$redeem_point_detail = new RedeemPointsDetails();
				$redeem_point_detail->setSale($sale);	
				$redeem_point_detail->setPrizeExchange($prizeExchange);	
				$redeem_point_detail->setPoints($points_to_used);	
				$redeem_point_detail->setCreatedAt(new \DateTime());
				$redeem_point_detail->setStaffId($staff->getStaffId());	
				$redeem_point_detail->setPointType($point_type);	
				$em->persist($redeem_point_detail);
				$em->flush();
				$points_nedeed = 0;
			}		
			$accrued_point_details = $em->getRepository('AppBundle:AccruedPointDetails')->findOneBy(array("accruedPointDetailsId" => $info["accrued_point_details_id"]));
			$accrued_point_details->setAvailablePoints(($info["available_points"]-$points_to_used));
			$em->persist($redeem_point_detail);
			$em->flush();
		}	
		return "ok";
	}
	
	
	public function getReddemPointsNeeded($staff_id,$redeem_points)
	{
		$detail = array();
	    $em = $this->getEntityManager();
	    $staff = $em->getRepository('AppBundle:Staff')->findOneBy(array("staffId" => $staff_id));
		$accruedPointDetails = $em->getRepository('AppBundle:AccruedPointDetails')->getAccruedPointsAvailable($staff_id);
		$points_nedeed = $redeem_points;
		foreach($accruedPointDetails as $info){
			$point_type = $em->getRepository('AppBundle:PointType')->findOneBy(array("pointTypeId" => $info["point_type_id"]));
			if($points_nedeed <= 0){
				break;
			}
			if($points_nedeed >= $info["available_points"]){
				$points_nedeed = $points_nedeed - $info["available_points"];
				$points_to_used = $info["available_points"];
				
				
				
			}else{
				$points_to_used = $points_nedeed;
				$points_nedeed = 0;
			}		
			if(isset($detail[$point_type->getPointType()] )){
				$detail[$point_type->getPointType()] = $detail[$point_type->getPointType()]+$points_to_used;
			}else{
				$detail[$point_type->getPointType()] = $points_to_used;
			}	
		}	
		return $detail;
	}

	/**
	 * Gets SmsIncoming of staff
	 *
	 * @param string $createdAt createdAt filter
	 * @param string $prize prizeExchange.prize.id filter
	 * @param string $points redeemPoints filter
	 * @param string $phone phone filter
	 * @param Staff $staff staff whos sms_incoming they want
	 * @return array PrizeExchange of staff
	 */
	public function getByStaff($createdAt, $prize, $points, $phone, $staff) {
	    $em = $this->getEntityManager();

	    $repo = $em->getRepository('AppBundle:PrizeExchange');
	    $query = $repo->createQueryBuilder('pe');

	    // Fixed parameter
	    $query
	        ->select(
	        	"pe.prizeExchangeId as id, pe.createdAt, p.displayName as prize,
	        	pe.redeemPoints as points, s.phoneMain as phone"
	        )
	        ->innerJoin('pe.staff', 's')
	        ->innerJoin('pe.prize', 'p')
	        ->where('pe.staff = :staff')
	        ->setParameter('staff', $staff)
	    ;

	    // Apply filters if necessary
	    if ($createdAt) {
	    	$createdAtE = new \DateTime($createdAt);
            $createdAtE->modify('+1 day');
	        $query
	            ->andWhere('pe.createdAt BETWEEN :createdAtS AND :createdAtE')
	            ->setParameter('createdAtS', $createdAt)
	            ->setParameter('createdAtE', $createdAtE)
	        ;
	    }

	    if ($prize) {
	        $query
	            ->andWhere('p.id = :prize')
	            ->andWhere('p.isActive = 1')
	            ->setParameter('prize', $prize)
	        ;
	    }

	    if ($points) {
	        $query
	            ->andWhere('pe.points = :points')
	            ->setParameter('points', $points)
	        ;
	    }

	    if ($phone) {
	        $query
	            ->andWhere('s.phoneMain LIKE :phone')
	            ->setParameter('phone', '%' . $phone . '%')
	        ;
	    }

	    return $query->getQuery()->getResult();
	}
}


