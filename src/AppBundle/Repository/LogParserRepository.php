<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\LogParser;
use AppBundle\Entity\ParserType;

class LogParserRepository extends EntityRepository {
    public function createSaleLog($user, $text){
        return $this->createLog($user, $text, 1);
    }

    public function createPointOfSaleLog($user, $text){
        return $this->createLog($user, $text, 2);
    }

    public function createStaffLog($user, $text){
        return $this->createLog($user, $text, 3);
    }

    public function createGoalLog($user, $text){
        return $this->createLog($user, $text, 4);
    }

    public function createLog($user, $text, $typeId){
        try {
            $error = new LogParser();
            $em = $this->getEntityManager();
            $error->setParserType($em->getReference('AppBundle\Entity\ParserType', $typeId));
            $error->setCreatedBy($user->getId());
            $error->setCreatedAt(new \DateTime());
            $error->setLogText($text);

            return $error;
        } catch (Exception $e) {
            return null;
        }
    }
}
