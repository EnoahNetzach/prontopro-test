<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository as BaseRepository;
use Doctrine\ORM\Query\Expr;

/**
 * ProfileRepository
 */
class ProfileRepository extends BaseRepository
{
    public function findProfileByIdJoined($id)
    {
        $query = $this->createQueryBuilder('p')
            ->select('p')
            ->innerJoin('p.profileExtended', 'pe')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id)
        ;

        return $query->getQuery()->getSingleResult();
    }

    public function findAllProfilesJoined()
    {
        $query = $this->createQueryBuilder('p')
            ->select('p')
            ->innerJoin('p.profileExtended', 'pe')
        ;

        return $query->getQuery()->getResult();
    }
}