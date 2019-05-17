<?php

namespace App\Access\Repository;

use App\Access\Document\User;
use Doctrine\ODM\MongoDB\LockMode;
use Doctrine\ODM\MongoDB\Query\Builder;
use FOS\UserBundle\Util\Canonicalizer;
use TransformationsBundle\Utilities\DateTimeConverter;

/**
 * @method User find($id, $lockMode = LockMode::NONE, $lockVersion = null)
 * @method User findOneBy(array $criteria)
 * @method User[] findBy(array $criteria, array $sort = null, $limit = null, $skip = null)
 * @method User[] findAll()
 */
class UserRepository extends AbstractRepository
{
    /**
     * @param string $username
     * @return \App\Access\Document\User|null
     */
    public function findOneByUsername(string $username)
    {
        $canonicalizer = new Canonicalizer();
        return $this->findOneBy(['usernameCanonical' => $canonicalizer->canonicalize($username)]);
    }

    /**
     * @param string $email
     * @return \App\Access\Document\User|null
     */
    public function findOneByEmail(string $email)
    {
        $canonicalizer = new Canonicalizer();
        return $this->findOneBy(['emailCanonical' => $canonicalizer->canonicalize($email)]);
    }

    /**
     * @param string $field
     * @param array $values
     * @return \App\Access\Document\User[]|\Doctrine\ODM\MongoDB\Cursor
     */
    public function findAllIn(string $field, array $values)
    {
        $qb = $this->createQueryBuilder();

        return $qb->field($field)->in($values)->getQuery()->execute();
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param string $orderBy
     * @param bool $reverse
     * @param array $criteria
     * @param bool $getCountInstead
     * @return \App\Access\Document\User[]|int int for last param if count requested
     */
    public function search(
        int $offset,
        int $limit,
        string $orderBy = 'id',
        bool $reverse = false,
        array $criteria = [],
        bool $getCountInstead = false
    ) {
        $qb = $this->createQueryBuilder();
        $qb->skip($offset)->limit($limit)->sort($orderBy, $reverse ? -1 : 1);

        if(isset($criteria['id'])) {
            if(! is_string($criteria['id'])) {
                throw new InvalidInputException('id must be a string or null');
            }
            $qb->field('id')->equals($criteria['id']);
        }

        if(isset($criteria['username'])) {
            if(! is_string($criteria['username'])) {
                throw new InvalidInputException('username must be a string or null');
            }
            $qb->field('username')->equals($criteria['username']);
        }

        if(isset($criteria['email'])) {
            if(! is_string($criteria['email'])) {
                throw new InvalidInputException('email must be a string or null');
            }
            $qb->field('email')->equals($criteria['email']);
        }

        if(isset($criteria['enabled'])) {
            if(! is_bool($criteria['enabled'])) {
                throw new InvalidInputException('enabled must be a boolean or null');
            }
            $qb->field('enabled')->equals($criteria['enabled']);
        }

        if(isset($criteria['roles'])) {
            if(! is_array($criteria['roles'])) {
                throw new InvalidInputException('roles must be an array or null');
            }
            $qb->field('roles')->all($criteria['roles']);
        }

        if(isset($criteria['createdAt'])) {
            $criteria['createdAt'] = DateTimeConverter::fromISO8601Safe($criteria['createdAt']);
            $qb->field('createdAt')->equals($criteria['createdAt']);
        }

        if(isset($criteria['updatedAt'])) {
            $criteria['updatedAt'] = DateTimeConverter::fromISO8601Safe($criteria['updatedAt']);
            $qb->field('updatedAt')->equals($criteria['updatedAt']);
        }

        return $getCountInstead ? $qb->getQuery()->count() : $qb->getQuery()->execute();
    }


    /**
     * Get new joined users orderred by createdAt
     *
     * @param int $offset
     * @param int $limit
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     * @return \Doctrine\ODM\MongoDB\Cursor|\App\Access\Document\User[]
     */
    public function getNewUsers(int $offset = 0, int $limit = 20)
    {
        $qb = $this->createQueryBuilder();
        $qb
           ->skip($offset)
           ->sort('createdAt', -1)
           ->select(['_id', 'email', 'username', 'createdAt', 'country'])
           ->limit($limit)
        ;

        /** @var \Doctrine\ODM\MongoDB\Cursor $result */
        $result = $qb
            ->getQuery()
            ->execute()
        ;

        $generator = $this->getJoinedUsersIterator($result);

        return iterator_to_array($generator);
    }
}