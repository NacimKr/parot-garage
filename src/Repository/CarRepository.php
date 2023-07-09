<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Car>
 *
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    public function save(Car $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Car $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByCars2(
        ?string $marque = null, 
        ?int $kilometrageMin = null,
        ?int $kilometrageMax = null,
        ?int $anneeMin = null,
        ?int $anneeMax = null,
        ?int $prixMin = null,
        ?int $prixMax = null,
    ):array
    {
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.isActive = :active')
            ->setParameter('active', 1);

        if (!empty($kilometrageMin) && !empty($kilometrageMax)) {
            $qb->andWhere('c.kilometrage >= :kilometersMin AND c.kilometrage <= :kilometersMax')
               ->setParameter('kilometersMin', $kilometrageMin)
               ->setParameter('kilometersMax', $kilometrageMax);
        }
        if (!empty($anneeMin) && !empty($anneeMax)) {
            $qb->andWhere('c.annee >= :anneMin AND c.annee <= :anneeMax')
               ->setParameter('anneMin', $anneeMin)
               ->setParameter('anneeMax', $anneeMax);
        }
        if (!empty($prixMin) && !empty($prixMax)) {
            $qb->andWhere('c.prix >= :prixMin AND c.prix <= :prixMax')
               ->setParameter('prixMin', $prixMin)
               ->setParameter('prixMax', $prixMax);
        }

        if (!empty($marque)) {
            $qb->andWhere('c.marque LIKE :marque')
               ->setParameter('marque', "%".$marque."%");
        }

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Car[] Returns an array of Car objects
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

//    public function findOneBySomeField($value): ?Car
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
