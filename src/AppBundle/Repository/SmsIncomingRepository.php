<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class SmsIncomingRepository extends EntityRepository
{
    /**
     * Search in database with search parameters
     *
     * @param string $sms LIKE parameter for sms_string column
     * @param string $phone phone to look for
     * @return DoctrineQuery query as doctrine object
     */
    public function search($sms, $phone) {
        $em = $this->getEntityManager();

        $repo = $em->getRepository('AppBundle:SmsIncoming');
        $query = $repo->createQueryBuilder('s');

        // Add parameters if necesary
        if ($sms !== NULL && $sms != '') {
            $query->where('s.smsString LIKE :sms')
                ->setParameter('sms', '%' . $sms . '%');
        }

        if ($phone !== NULL && $phone != '') {
            $query->where('s.phone LIKE :phone')
                ->setParameter('phone', '%' . $phone . '%');
        }

        $query->orderBy('s.createdAt', 'DESC')
            ->getQuery();

        return $query;
    }

    /**
     * Gets SmsIncoming of staff
     *
     * @param string $createdAt createdAt filter
     * @param string $phone phone filter
     * @param string $smsString smsString filter
     * @param Staff $staff staff whos sms_incoming they want
     * @return array SmsIncoming of staff
     */
    public function getByStaff($createdAt, $phone, $smsString, $staff) {
        $em = $this->getEntityManager();

        $repo = $em->getRepository('AppBundle:SmsIncoming');
        $query = $repo->createQueryBuilder('si');

        // Fixed parameter
        $query
            ->select('si.smsIncomingId as id, si.createdAt, si.phone, si.smsString, si.parseResult')
            ->where('si.staff = :staff')
            ->setParameter('staff', $staff)
        ;

        // Apply filters if necessary
        if ($createdAt) {
            // Get next day
            $createdAtE = new \DateTime($createdAt);
            $createdAtE->modify('+1 day');
            $query
                ->andWhere('si.createdAt BETWEEN :createdAtS AND :createdAtE')
                ->setParameter('createdAtS', $createdAt)
                ->setParameter('createdAtE', $createdAtE)
            ;
        }

        if ($phone) {
            $query
                ->andWhere('si.phone LIKE :phone')
                ->setParameter('phone', '%' . $phone . '%')
            ;
        }

        if ($smsString) {
            $query
                ->andWhere('si.smsString LIKE :smsString')
                ->setParameter('smsString', '%' . $smsString . '%')
            ;
        }

        return $query->getQuery()->getResult();
    }
}
