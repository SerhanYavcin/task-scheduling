<?php

namespace App\Repository;

use App\Entity\TaskProvider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TaskProvider>
 *
 * @method TaskProvider|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaskProvider|null findOneBy(array $criteria, array $orderBy = null)
 * @method TaskProvider[]    findAll()
 * @method TaskProvider[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskProviderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaskProvider::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(TaskProvider $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(TaskProvider $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function isUrlExists(string $url): bool
    {
        return (bool) $this->findOneBy(['url' => $url]);
    }


    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function isCodeExists(string $code): bool
    {
        return (bool) $this->findOneBy(['code' => $code]);
    }

    // /**
    //  * @return TaskProvider[] Returns an array of TaskProvider objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TaskProvider
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
