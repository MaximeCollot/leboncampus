<?php

namespace Permanence;

use Permanence\Permanence;
use Doctrine\DBAL\Connection;
 
class PermanenceProvider
{
    private $conn;
 
    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    public function getCountByDate($date) {
        $queryBuilder = $this->conn->createQueryBuilder();
        $queryBuilder->add('select', 'slot, count(*) as count')
        ->from('Permanence', 'p')
        ->where('p.date = :date')
        ->add('groupBy', 'p.slot ASC')
        ->setParameter('date', $date)
        ;

        return $queryBuilder->execute()->fetchAll();
    }
 
    public function getResultsByDateAndSlot($date, $slot) {
        $queryBuilder = $this->conn->createQueryBuilder();
        $queryBuilder->select('*')
        ->from('Permanence', 'p')
        ->where('p.date = :date AND p.slot = :slot')
        ->setParameter('date', $date)
        ->setParameter('slot', $slot)
        ;

        return $queryBuilder->execute()->fetchAll();
    }

    public function save(Permanence $permanence) {

        //TODO gérer les erreurs de doublon
        $queryBuilder = $this->conn->createQueryBuilder();
        $queryBuilder->select('*')
        ->from('Permanence', 'p')
        ->where('p.date = :date AND p.slot = :slot AND p.name = :name')
        ->setParameter('date', $permanence->getDate())
        ->setParameter('slot', $permanence->getSlot())
        ->setParameter('name', $permanence->getName())
        ;
        $result = $queryBuilder->execute()->rowCount();
        var_dump($result);

        if ( $result == 0){
            $query = $this->conn->createQueryBuilder();
            $query->insert('permanence')
            ->setValue('date', '?')
            ->setValue('slot', '?')
            ->setValue('name', '?')
            ->setParameter(0, $permanence->getDate())
            ->setParameter(1, $permanence->getSlot())
            ->setParameter(2, $permanence->getName())
            ;

            $query->execute();
        }
    }
}