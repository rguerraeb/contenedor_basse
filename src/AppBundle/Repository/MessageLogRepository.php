<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;


class MessageLogRepository extends EntityRepository
{
    /**
     * Search in database with search parameters
     *
     * @param string $message message assigned message
     * @param string $name name of the staff assigned to message
     * @param string $citizenId citizenId of the staff assigned to message
     * @param string $phone phone of the staff assigned to message
     * @return DoctrineQuery query as doctrine object
     */
    public function search($message, $name, $citizenId, $phone) {
        $em = $this->getEntityManager();

        $repo = $em->getRepository('AppBundle:MessageLog');
        $query = $repo->createQueryBuilder('ml');

        // Fixed parameter
        $query->where('ml.message = :message')
            ->setParameter('message', $message);

        $isJoined = false;

        // Add parameters if necesary
        if ($name !== NULL && $name != '') {
            // Join is necessary
            $query->join('ml.staff', 's');
            $isJoined = true;

            $query->andWhere('s.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }

        // Add parameters if necesary
        if ($citizenId !== NULL && $citizenId != '') {
            // Join is necessary
            if (!$isJoined) {
                $query->join('ml.staff', 's');
                $isJoined = true;
            }

            $query->andWhere('s.citizenId LIKE :citizenId')
                ->setParameter('citizenId', '%' . $citizenId . '%');
        }

        // Add parameters if necesary
        if ($phone !== NULL && $phone != '') {
            // Join is necessary
            if (!$isJoined) {
                $query->join('ml.staff', 's');
                $isJoined = true;
            }

            $query->andWhere('s.phone LIKE :phone')
                ->setParameter('phone', '%' . $phone . '%');
        }

        return $query->getQuery();
    }

    /**
     * Counts how many staffs are assigned to message grouped by MessageBag
     *
     * @param Message $message message filter
     * @return array() each message bag with how many staff has assigned
     */
    public function count($message) {
        $em = $this->getEntityManager();

        $repo = $em->getRepository('AppBundle:MessageLog');
        $query = $repo->createQueryBuilder('ml');

        $query->select('IDENTITY(ml.messageBag) as mbId, COUNT(ml.staff) as total');

        // Fixed parameter
        $query->where('ml.message = :message')
            ->setParameter('message', $message);

        $query->groupBy('ml.messageBag');

        return $query->getQuery()->getResult();
    }

    /**
     * Gets messages sent to a staff
     *
     * @param Staff $staff staff who received the messages
     * @return array MessageLogs sent to staff
     */
    public function getByStaff($staff) {
        $em = $this->getEntityManager();

        $repo = $em->getRepository('AppBundle:MessageLog');
        $query = $repo->createQueryBuilder('ml');

        // Fixed parameter
        $query
            ->innerJoin('ml.message', 'm')
            ->where('ml.staff = :staff')
            ->andWhere('ml.messageLogStatus = 2')
            ->setParameter('staff', $staff)
            ->orderBy('m.sendDate', 'DESC');


        return $query->getQuery()->getResult();
    }
}
