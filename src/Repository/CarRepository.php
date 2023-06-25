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

    public function findByCars(
        ?string $value = null, 
        ?int $kilometrage = null,
        ?int $annee = null,
        ?int $prix = null,
    ):array
    {
        $cars = $this->createQueryBuilder("c");

        //Utiliser la clause LIKE pour voir s'il contient le mot recherhces
        if($value !== null){
            //Rechercuqe par nom
            $cars->andWhere('c.marque LIKE :val')
                ->setParameter(':val', "%{$value}%");
        }elseif(isset($kilometrage)){
            //Rechercuqe par kilometrage
            $cars->andWhere('c.kilometrage <= :val')
                ->setParameter(':val', $kilometrage);
        }elseif(isset($annee)){
            //Rechercuqe par année
            $cars->andWhere('c.annee <= :val')
                ->setParameter(':val', $annee);
        }elseif(isset($prix)){
            //Rechercuqe par prix
            $cars->andWhere('c.prix <= :val')
                ->setParameter(':val', $prix);
        }

        $cars = $cars->getQuery()->getResult();
        return $cars;
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
