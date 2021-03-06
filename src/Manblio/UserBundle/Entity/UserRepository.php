<?php

namespace Manblio\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
	public function SearchUser($keyWord)
    {
        $keyWord = str_replace(' ', '%', trim($keyWord));
		$qb = $this->createQueryBuilder('u')  //add select and array for JSON
            ->where('u.enabled = 1 AND u.id!=1 AND u.username LIKE :string OR u.email LIKE :string')
            ->setParameter('string', '%'.$keyWord.'%')
            ->setMaxResults('10');
 
    return $qb->getQuery()
               ->getResult();
 
    }

    public function findResiduel($date)
    {
 
		$qb = $this->createQueryBuilder('u')  //add select and array for JSON
            ->where('u.enabled = 0 AND u.id!=1 AND u.lastLogin < :date')
            ->setParameter('date', $date);
    return $qb->getQuery()
               ->getResult();
 
    }

    public function findUnused($date)
    {
 
		$qb = $this->createQueryBuilder('u')  //add select and array for JSON
            ->where('u.lastLogin < :date AND u.id!=1')
            ->setParameter('date', $date);
    return $qb->getQuery()
               ->getResult();
 
    }
}
