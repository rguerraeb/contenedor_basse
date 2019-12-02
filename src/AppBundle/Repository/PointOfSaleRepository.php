<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class PointOfSaleRepository extends EntityRepository
{
    /**
     * Search in database with search parameters
     *
     * @param string $businessName LIKE parameter for businessName column
     * @param string $taxIdentifier taxIdentifier to look for
     * @param string $pointOfSaleInnerId pointOfSaleInnerId to look for
     * @return DoctrineQuery query as doctrine object
     */
    public function search($businessName, $taxIdentifier, $pointOfSaleInnerId) {
        $em = $this->getEntityManager();

        $repo = $em->getRepository('AppBundle:PointOfSale');
        $query = $repo->createQueryBuilder('pos');

        // Add parameters if necesary
        $contAdded = 0;
        if ($businessName !== NULL && $businessName != '') {
            $query->where('pos.businessName LIKE :businessName')
                ->setParameter('businessName', '%' . $businessName . '%');
        }

        if ($taxIdentifier !== NULL && $taxIdentifier != '') {
            $query->andWhere('pos.taxIdentifier LIKE :taxIdentifier')
                ->setParameter('taxIdentifier', '%' . $taxIdentifier . '%');
        }

        if ($pointOfSaleInnerId !== NULL && $pointOfSaleInnerId != '') {
            $query->andWhere('pos.pointOfSaleInnerId LIKE :pointOfSaleInnerId')
                ->setParameter('pointOfSaleInnerId', '%' . $pointOfSaleInnerId . '%');
        }
        
        $query->addOrderBy("pos.createdAt", "DESC");

        $query->getQuery();

        return $query;
    }
    
    public function getStaffsPos($staffId) {
        $em = $this->getEntityManager();
        
        $repo = $em->getRepository('AppBundle:StaffPointOfSale');
        $query = $repo->createQueryBuilder('spos')
        ->select("pos.pointOfSaleId, pos.businessName, pos.groupName, pos.pointOfSaleInnerId")
        ->innerJoin("spos.pointOfSale", "pos")
        ->where("spos.staff = :staffId")->setParameter("staffId", $staffId)
        ->andWhere("spos.status = 'ACTIVE'")
        ;
        
        $query->addOrderBy("pos.businessName", "ASC");
        
        $res = $query->getQuery();
        
        return $res->execute();
    }
    
    public function getUserPos($userId = 0) {
         $query = "SELECT pos.point_of_sale_id, pos.business_name , pos.group_name, pos.point_of_sale_inner_id , spos.is_active
				 FROM point_of_sale pos 
				 LEFT JOIN (select point_of_sale_id, is_active from user_point_of_sale where user_id = $userId ) spos on pos.point_of_sale_id = spos.point_of_sale_id 
				 AND spos.is_active = '1' 
				 AND pos.status = '1' 
				 ORDER BY pos.business_name ASC";
        
        $res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
        $res->execute ();
        
        return $res->fetchAll();
    }

	 public function getUpdateUserPos($userId , $pointsOfSales) {
        $query = "update user_point_of_sale set is_active = 0 where point_of_sale_id not in ($pointsOfSales) and user_id = $userId ";
        
        $res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
        $res->execute ();
        
        return "updated";
    }


}
