<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;


class MessageBagRepository extends EntityRepository
{
    /**
     * Gets the total of messages available
     *
     * @return number of total available messages
     */
    public function messagesAvailable() {
        $em = $this->getEntityManager();

        $repo = $em->getRepository('AppBundle:MessageBag');
        $query = $repo->createQueryBuilder('mb');

        $query->select('sum(mb.available)');
        return $query->getQuery()->getResult(Query::HYDRATE_SINGLE_SCALAR);
    }

    /**
     * Gets the newest message bag
     *
     * @return MessageBag newest MessageBag stored
     */
    public function newest() {
        $em = $this->getEntityManager();

        $repo = $em->getRepository('AppBundle:MessageBag');
        $query = $repo->createQueryBuilder('mb');

        $query->orderBy('mb.createdAt', 'ASC');
        $query->setMaxResults(1);

        return $query->getQuery()->getOneOrNullResult();
    }

    /**
     * Gets message bags with available > 0
     *
     * @return array MessageBags with available messages
     */
    public function findAvailableBags() {
        $em = $this->getEntityManager();

        $repo = $em->getRepository('AppBundle:MessageBag');
        $query = $repo->createQueryBuilder('mb');

        $query->where('mb.available > 0');

        $query->orderBy('mb.createdAt', 'DESC');

        return $query->getQuery()->getResult();
    }
}
