<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class MessageRepository extends EntityRepository
{
    /**
     * Search in database with search parameters
     *
     * @param string $sms LIKE parameter for sms column
     * @param string $name LIKE parameter for name column
     * @param string $start inferior limit for sendDate
     * @param string $end superior limit for sendDate
     * @param integer $sent if status is sent or not
     * @return DoctrineQuery query as doctrine object
     */
    public function search($name, $sms, $start, $end, $sent) {
        $em = $this->getEntityManager();

        $repo = $em->getRepository('AppBundle:MessageLog');
        $query = $repo->createQueryBuilder('ml');

        $query->select(
            "m.messageId, m.createdAt, m.sendDate, u.firstName, u.lastName,
            m.name as mName, COUNT(ml.staff), ms.name as msName, ms.messageStatusId as msId"
        );

        $query->join('ml.message', 'm');
        $query->join('m.createdBy', 'u');
        $query->join('m.messageStatus', 'ms');

        // Add parameters if necesary
        if ($sms !== NULL && $sms != '') {
            $query->andWhere('m.sms LIKE :sms')
                ->setParameter('sms', '%' . $sms . '%');
        }

        if ($name !== NULL && $name != '') {
            $query->andWhere('m.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }

        if ($start !== NULL && $start != '') {
            $query->andWhere('m.sendDate > :start')
                ->setParameter('start', $start);
        }

        if ($end !== NULL && $end != '') {
            $query->andWhere('m.sendDate < :end')
                ->setParameter('end', $end);
        }

        // Don't show eliminated ones
        $query->andWhere('m.messageStatus != 3');

        if ($sent == 1) {
            $query->andWhere('m.messageStatus = 4');
        }
        else if ($sent == 0) {
            $query->andWhere('m.messageStatus != 4');
        }

        $query->addOrderBy('m.createdAt', 'DESC');

        $query->groupBy('m.messageId');

        return $query->getQuery()->getResult();
    }

    /**
     * Search for active messages in an interval of time
     *
     * @param DateTime $start start of interval
     * @param DateTime $end end of interval
     * @return array() active Messages found
     */
    public function between($start, $end) {
        $em = $this->getEntityManager();

        $repo = $em->getRepository('AppBundle:Message');
        $query = $repo->createQueryBuilder('m');

        $query->where($query->expr()->between(
                'm.sendDate',
                ':start',
                ':end'
            )
        )
       ->setParameters(
            array(
                'start' => $start,
                'end' => $end
            )
        );

       $query->andWhere(
            'm.messageStatus = 1'
        );

        return $query->getQuery()->getResult();
    }

    /**
     * Finds names of messages using name by filter
     *
     * @param string $name name of message
     * @param integer $limit how many results
     * @param array() messages names
     */
    public function getNames($name, $limit = 5) {
        $em = $this->getEntityManager();

        $repo = $em->getRepository('AppBundle:Message');
        $query = $repo->createQueryBuilder('m');

        $query->select('m.name');

        $query->andWhere('m.name LIKE :name')
            ->setParameter('name', $name . '%');

        $query->setMaxResults($limit);

        return $query->getQuery()->getResult();
    }

    /**
     * Finds names of messages using name by filter
     *
     * @param string $sms sms of message
     * @param integer $limit how many results
     * @param array() messages smss
     */
    public function getSms($sms, $limit = 5) {
        $em = $this->getEntityManager();

        $repo = $em->getRepository('AppBundle:Message');
        $query = $repo->createQueryBuilder('m');

        $query->select('m.sms');

        $query->andWhere('m.sms LIKE :sms')
            ->setParameter('sms', $sms . '%');

        $query->setMaxResults($limit);

        return $query->getQuery()->getResult();
    }
}
