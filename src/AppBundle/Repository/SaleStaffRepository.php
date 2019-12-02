<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\StaffPoints;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class SaleStaffRepository extends EntityRepository
{
    public function getStaffPoints($staffId)
    {
        $query = "select sum(points) as points from sale_staff where staff_id = $staffId";
        $res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
        $res->execute ();
        return $res->fetchAll ();
    }


	public function getStaffAvailablePoints($staffId)
    {
        $query = "select sum(available_points) as points from accrued_point_details where staff_id = $staffId";
        $res = $this->getEntityManager ()->getConnection ()->prepare ( $query );
        $res->execute ();
        return $res->fetchAll ();
    }

    /**
     * Gets SalesStaff of staff
     *
     * @param string $issuedAt sale.issuedAt filter
     * @param string $skuCode sale.skuCode filter
     * @param int $skuCategory sale.sku.skuCategory.skuCategoryId filter
     * @param string $cc sale.sku.cc filter
     * @param string $points saleStaff.points filter
     * @param string $invoiceNumber sale.invoiceNumber filter
     * @param Staff $staff saleStaff.staff
     * @return array Sales of staff
     */
    public function getByStaff($issuedAt, $skuCode, $skuCategory, $cc, $points, $invoiceNumber, $staff) {
        $em = $this->getEntityManager();

        $repo = $em->getRepository('AppBundle:SaleStaff');
        $query = $repo->createQueryBuilder('ss');

        // Fixed parameter
        $query
            ->select('s.issuedAt, s.skuCode, sc.name as skCategory, sk.cc, ss.points, s.invoiceNumber')
            ->innerJoin('ss.sale', 's')
            ->innerJoin('s.sku', 'sk')
            ->innerJoin('sk.skuCategory', 'sc')
            ->where('ss.staff = :staff')
            ->setParameter('staff', $staff)
        ;

        // Apply filters if necessary
        if ($issuedAt) {
            $query
                ->andWhere('s.issuedAt = :issuedAt')
                ->setParameter('issuedAt', $issuedAt)
            ;
        }

        if ($skuCode) {
            $query
                ->andWhere('s.skuCode LIKE :skuCode')
                ->setParameter('skuCode', '%' . $skuCode . '%')
            ;
        }

        if ($skuCategory) {
            $query
                ->andWhere('sc.skuCategoryId = :skuCategory')
                ->setParameter('skuCategory', $skuCategory)
            ;
        }

        if ($cc) {
            $query
                ->andWhere('sk.cc = :cc')
                ->setParameter('cc', $cc)
            ;
        }

        if ($points) {
            $query
                ->andWhere('ss.points = :points')
                ->setParameter('points', $points)
            ;
        }

        if ($invoiceNumber) {
            $query
                ->andWhere('s.invoiceNumber = :invoiceNumber')
                ->setParameter('invoiceNumber', $invoiceNumber)
            ;
        }

        return $query->getQuery()->getResult();
    }
    
    
    public function getTotalMezclas($staffId) {
        
        $em = $this->getEntityManager ();
        $query = "
            SELECT
                sum(ppd.total_points) as total_puntos_mezclas, sum(ppd.amount) total_sacos
            FROM
                loyalty_cempro.purchased_product_detail ppd
                    INNER JOIN
                sale s ON s.sale_id = ppd.sale_id
                    INNER JOIN
                sale_staff ss ON ss.sale_id = s.sale_id
            WHERE
                ss.staff_id = :staffId
        ";
        
        // Values to bind to query
        $values = array("staffId" => $staffId);
        
        // Values types
        $types = array(\PDO::PARAM_INT);
        
        $conn = $em->getConnection ();
        $stmt = $conn->executeQuery($query, $values, $types);
        
        $result = $stmt->fetchAll();
        
        return $result[0];
    }
    
    public function getTotalBagPurchase($staffId) {
        
        $em = $this->getEntityManager ();
        $query = "
            SELECT 
                SUM(sale.quantity) total
            FROM
                loyalty_cempro.sale_staff ss
                    INNER JOIN
                staff s ON s.staff_id = ss.staff_id
                    INNER JOIN
                sale sale ON sale.sale_id = ss.sale_id
            WHERE
                ss.was_seller = 1
                    AND ss.is_cancelled = 0
                    AND sale.sku_id = 1
                    AND ss.staff_id = :staffId
        ";
        
        // Values to bind to query
        $values = array("staffId" => $staffId);
        
        // Values types
        $types = array(\PDO::PARAM_INT);
        
        $conn = $em->getConnection ();
        $stmt = $conn->executeQuery($query, $values, $types);
        
        $result = $stmt->fetchAll();
        
        return $result[0]["total"];
    }
    
    
}