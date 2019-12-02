<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\StaffPoints;
use AppBundle\Entity\SaleStaff;
use Doctrine\DBAL\Connection;

class SaleRepository extends EntityRepository
{
    /**
     * Search in database with search parameters
     *
     * @param string $skuCode LIKE parameter for skuCode column
     * @param string $skuFilterString LIKE parameter for skuFilterString column
     * @param string $pointOfSaleInnerId LIKE parameter for point of sale.innerId
     * @param string $issuedAt date when it was issued
     * @param string $invoiceNumber the invoiceNumber
     * @param boolean $isAssigned if sale exists in sale_staff table
     * @return DoctrineQuery query as doctrine object
     */
    public function search(
        $skuCode,
        $skuFilterString,
        $pointOfSaleInnerId,
        $issuedAt,
        $invoiceNumber,
        $isAssigned
    ) {
        $em = $this->getEntityManager();

        $repo = $em->getRepository('AppBundle:Sale');
        $query = $repo->createQueryBuilder('s');

        // Select part
        $query->select(
            's as sale, ss.saleStaffId'
        );

        // From part
        // Add left join to know if its assigned
        $query->leftJoin(
            'AppBundle:SaleStaff',
            'ss',
            'WITH',
            'ss.sale = s'
        );


        // Add parameters if necesary
        if ($skuCode !== NULL && $skuCode != '') {
            $query->andWhere('s.skuCode LIKE :skuCode')
                ->setParameter('skuCode', '%' . $skuCode . '%');
        }

        // Add parameters if necesary
        if ($skuFilterString !== NULL && $skuFilterString != '') {
            $query->andWhere('s.skuFilterString LIKE :skuFilterString')
                ->setParameter('skuFilterString', '%' . $skuFilterString . '%');
        }

        // Add parameters if necesary
        if ($pointOfSaleInnerId !== NULL && $pointOfSaleInnerId != '') {
            $query->innerJoin('s.pointOfSale', 'pos');
            $query->andWhere('pos.pointOfSaleInnerId LIKE :pointOfSaleInnerId')
                ->setParameter('pointOfSaleInnerId', '%' . $pointOfSaleInnerId . '%');
        }

        // Add parameters if necesary
        if ($issuedAt !== NULL && $issuedAt != '') {
            $query->andWhere('s.issuedAt =:issuedAt')
                ->setParameter('issuedAt', $issuedAt);
        }

        // Add parameters if necesary
        if ($invoiceNumber !== NULL && $invoiceNumber != '') {
            $query->andWhere('s.invoiceNumber = :invoiceNumber')
                ->setParameter('invoiceNumber', $invoiceNumber);
        }

        if ($isAssigned !== NULL) {
            if ($isAssigned) {
                $query->andWhere('ss.saleStaffId IS NOT NULL');
            }
            else {
                $query->andWhere('ss.saleStaffId IS NULL');
            }
        }

        // Add group by to distinct
        $query->groupBy('s.saleId');
        $query->orderBy("s.saleId", "DESC");

        $query = $query->getQuery();

        return $query->getResult();
    }

    /**
     * Find not assigned sales
     *
     * @param string $skuCode parameter to filter in query
     * @param string $skuFilterString LIKE parameter for skuFilterString column
     * @param string $pointOfSaleInnerId LIKE parameter for point of sale.innerId
     * @return DoctrineQuery query as doctrine object
     */
    public function notInSearch($skuCode, $skuFilterString, $pointOfSaleInnerId) {
        $em = $this->getEntityManager();


        $saleStaffs = $em
            ->createQueryBuilder()
            ->from('AppBundle:SaleStaff', 'ss')
            ->select('IDENTITY(ss.sale)');

        $qb = $this->createQueryBuilder('s');
        $sales = $qb
            ->where(
                $qb->expr()->notIn('s', $saleStaffs->getDQL())
            );

        // Add parameters if necesary
        if ($skuCode !== NULL && $skuCode != '') {
            $sales->andWhere('s.skuCode LIKE :skuCode')
                ->setParameter('skuCode', '%' . $skuCode . '%');
        }

        // Add parameters if necesary
        if ($skuFilterString !== NULL && $skuFilterString != '') {
            $sales->andWhere('s.skuFilterString LIKE :skuFilterString')
                ->setParameter('skuFilterString', '%' . $skuFilterString . '%');
        }

        // Add parameters if necesary
        if ($pointOfSaleInnerId !== NULL && $pointOfSaleInnerId != '') {
            $sales->innerJoin('s.pointOfSale', 'pos');
            $sales->andWhere('pos.pointOfSaleInnerId LIKE :pointOfSaleInnerId')
                ->setParameter('pointOfSaleInnerId', '%' . $pointOfSaleInnerId . '%');
        }

        return $sales
            ->getQuery();
    }

    /**
     * Calculates sales total in a period of time
     *
     * @param Staff $staff sales of this staff
     * @param string $start date of when to start looking for sales
     * @param string $end date of when to end looking for sales
     * @return float total of sales in months
     */
    public function count($staff, $start, $end){
        $em = $this->getEntityManager ();
        $query = "
            SELECT
                COUNT(*) as total
            FROM
                sale_staff ss
            WHERE
                ss.created_at BETWEEN ? AND ?
                AND ss.staff_id = ?
                AND ss.was_seller = 1
                AND ss.is_cancelled = 0
        ";

        // Values to bind to query
        $values = array(
            $start,
            $end,
            $staff->getStaffId()
        );

        // Values types
        $types = array(
            \PDO::PARAM_STR,
            \PDO::PARAM_STR,
            \PDO::PARAM_INT,
        );

        $conn = $em->getConnection ();
        $stmt = $conn->executeQuery($query, $values, $types);

        $result = $stmt->fetchAll();

        if ($result && sizeof($result) > 0) {
            return $result[0]['total'];
        }

        return 0;
    }

    /**
     * Calculates sales total in a period of time using lower jobPositions
     *
     * @param Staff $staff sales of this staff
     * @param string $start date of when to start looking for sales
     * @param string $end date of when to end looking for sales
     * @return float total of sales in months
     */
    public function countHierarchy($staff, $start, $end){
        $em = $this->getEntityManager ();

        // Look for all job positions below in hierarchy
        $lowerJobPositions = array();
        $tempLowerIds = array(
            $staff->getJobPosition()->getId()
        );
        $findLower = true;

        while ($findLower) {
            $tempJP = $em->getRepository('AppBundle:JobPositionParent')
                ->findLower($tempLowerIds);

            // Condition to stop looking
            if (!$tempJP || sizeof($tempJP) == 0) {
                $findLower = false;
            }
            else {
                $tempLowerIds = array();

                // Add them to lower job positions array
                foreach ($tempJP as $lowerJob) {
                    $childId = $lowerJob['child_id'];
                    if (!in_array($childId, $lowerJobPositions)) {
                        // Add to temp ids array to start new search
                        array_push($tempLowerIds, $childId);

                        // Add to unique job positions
                        array_push($lowerJobPositions, $childId);
                    }
                }
            }
        }

        // Get sales
        $query = "
            SELECT
                COUNT(*) as total
            FROM
                sale_staff ss
                INNER JOIN
                    staff s ON (ss.staff_id = s.staff_id)
            WHERE
                ss.created_at BETWEEN ? AND ?
                AND s.job_position_id IN (?)
                AND ss.was_seller = 1
                AND ss.is_cancelled = 0
                AND s.staff_id IN (
                    SELECT
                        staff_id
                    FROM
                        staff_point_of_sale
                    WHERE
                        point_of_sale_id IN (
                            SELECT
                                point_of_sale_id
                            FROM
                                staff_point_of_sale
                            WHERE
                                staff_id = ?
                                AND status = 'ACTIVE'
                        )
                        AND status = 'ACTIVE'
                )
        ";

        // Values to bind to query
        $values = array(
            $start,
            $end,
            $lowerJobPositions,
            $staff->getStaffId()
        );

        // Values types
        $types = array(
            \PDO::PARAM_STR,
            \PDO::PARAM_STR,
            Connection::PARAM_INT_ARRAY,
            \PDO::PARAM_INT
        );

        $conn = $em->getConnection ();
        $stmt = $conn->executeQuery($query, $values, $types);

        $result = $stmt->fetchAll();

        if ($result && sizeof($result) > 0) {
            return $result[0]['total'];
        }

        return 0;
    }

    /**
     * Finds sales using arrays of filters
     *
     * @param array $states States from Staff
     * @param array $cities City from Staff
     * @param array $saleChannels SaleChannel from PointOfSales of Staff
     * @param array $pointsOfSale PointOfSales from StaffPointOfSale
     * @param array $jobPositions JobPosition from Staff
     * @param array $skus Sku from Sale
     * @param string $fromDate Sale.createdAt has to be greater than this
     * @param string $toDate Sale.createdAt has to be lower than this
     */
    public function findByMultiple(
        $states, $cities, $saleChannels, $pointOfSales,
        $jobPositions, $skus, $fromDate, $toDate) {
        $em = $this->getEntityManager();

        // Variables to see if joins have been made
        $isPosJoined = false;

        $repo = $em->getRepository('AppBundle:SaleStaff');
        $query = $repo->createQueryBuilder('ss');

        $query->select('ss');

        // Don't use cancelled sales
        $query->andWhere('ss.isCancelled = 0');

        if (sizeof($states) > 0 || sizeof($cities) > 0 || sizeof($jobPositions) > 0
            || sizeof($pointOfSales) > 0 || sizeof($saleChannels) > 0) {
            // Join with staff
            $query
                ->join('ss.staff', 's')
                ->andWhere('s.staffStatus = 2');

            if (sizeof($pointOfSales) > 0 || sizeof($saleChannels) > 0) {
                // Join with staff point of sale
                $query
                    ->from('AppBundle:StaffPointOfSale', 'spos')
                    ->andWhere('spos.staff = s')
                    ->andWhere("spos.status = 'ACTIVE'");

                if (sizeof($saleChannels) > 0) {
                    // Join with point of sale
                    $query->join('spos.pointOfSale', 'pos');
                }
            }
        }

        if (sizeof($states) > 0) {
            // Add filter
            $query
                ->andWhere('s.state IN (:states)')
                ->setParameter('states', $states);
        }

        if (sizeof($cities) > 0) {
            // Add filter
            $query
                ->andWhere('s.city IN (:cities)')
                ->setParameter('cities', $cities);
        }

        if (sizeof($jobPositions) > 0) {
            // Add filter
            $query
                ->andWhere('s.jobPosition IN (:jobPositions)')
                ->setParameter('jobPositions', $jobPositions);
        }

        if (sizeof($pointOfSales) > 0) {
            // Add filter
            $query
                ->andWhere('spos.pointOfSale IN (:pointOfSales)')
                ->setParameter('pointOfSales', $pointOfSales);
        }

        if (sizeof($saleChannels) > 0) {
            // Add filter
            $query
                ->andWhere('pos.saleChannel IN (:saleChannels)')
                ->setParameter('saleChannels', $saleChannels)
            ;
        }

        if (sizeof($skus) > 0) {
            // Add filter
            $query
                ->andWhere('ss.sku IN (:skus)')
                ->setParameter('skus', $skus)
            ;
        }

        if ($fromDate) {
            $query
                ->andWhere('ss.createdAt > :from')
                ->setParameter('from', $fromDate)
            ;
        }

        if ($toDate) {
            $query
                ->andWhere('ss.createdAt < :to')
                ->setParameter('to', $toDate)
            ;
        }

        $query
            ->groupBy('ss')
            ->orderBy('ss.staff');

        return $query->getQuery()->getResult();
    }

    public function getReverseList($skuCode = 0) {

        $em = $this->getEntityManager();

        $repo = $em->getRepository('AppBundle:SaleReverse');
        $query = $repo->createQueryBuilder('sr');

        $query->select('sr.saleReverseId, sr.skuCode, sr.status, sr.createdAt')
            ->where("sr.status != 'ELIMINADO'")
            ;
        if ($skuCode) {
            $query->andWhere("sr.skuCode like '%$skuCode%'");
        }

        $query->orderBy("sr.createdAt", "DESC");

        return $query->getQuery()->getResult();
    }
    
    
    public function getTotalPointsMezclas($staffId) {
        
        $em = $this->getEntityManager ();
        $query = "
            SELECT 
                sum(ppd.total_points) as total_puntos_mezclas
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
        
        return $result[0]["total_puntos_mezclas"];
    }
    
}