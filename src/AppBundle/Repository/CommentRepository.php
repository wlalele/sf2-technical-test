<?php

namespace AppBundle\Repository;

/**
 * CommentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommentRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByGithubUsername($username)
    {
        $qb = $this->createQueryBuilder('c');

        $qb->where(
            $qb->expr()->like('c.repository', $qb->expr()->literal($username.'/%'))
        );
        return $qb->getQuery()->getResult();
    }
}
