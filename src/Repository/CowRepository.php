<?php

namespace App\Repository;

use App\Entity\Cow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cow>
 *
 * @method Cow|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cow|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cow[]    findAll()
 * @method Cow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cow::class);
    }

    public function save(Cow $entity, bool $flush = false): void
    {
        $entity = $entity->setAbate($entity);
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Cow $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByAbate(): array {
        $abate = $this->getEntityManager()->createQuery(
            'SELECT cow
            FROM App\Entity\Cow cow
            WHERE cow.isAbate = true AND cow.isAlive = true'
        );
        return $abate->getResult();
    }

    public function findByisNotAlive(): array {
        $alive = $this->getEntityManager()->createQuery(
            'SELECT cow
            FROM App\Entity\Cow cow
            WHERE cow.isAlive = false'
        );
        return $alive->getResult();
    }

    public function findByisAlive(): array {
        $alive = $this->getEntityManager()->createQuery(
            'SELECT cow
            FROM App\Entity\Cow cow
            WHERE cow.isAlive = true'
        );
        return $alive->getResult();
    }

//    /**
//     * @return Cow[] Returns an array of Cow objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Cow
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
