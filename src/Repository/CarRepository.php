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
        ?string $marque, 
        ?int $kilometrage,
        ?int $annee,
        ?int $prix,
    ):array
    {
        $cars = $this->createQueryBuilder("c");

        //Utiliser la clause LIKE pour voir s'il contient le mot recherhces
        if(isset($marque)){
            //Rechercuqe par nom
            $cars->andWhere('c.marque LIKE :val')
                ->setParameter(':val', "%".$marque."%");
        }
        
        if(isset($kilometrage)){
            //Rechercuqe par kilometrage
            $cars->andWhere('c.kilometrage >= :val')
                ->setParameter(':val', $kilometrage);
        }elseif(isset($annee)){
            //Rechercuqe par année
            $cars->andWhere('c.annee >= :val')
                ->setParameter(':val', $annee);
        }elseif(isset($prix)){
            //Rechercuqe par prix
            $cars->andWhere('c.prix >= :val')
                ->setParameter(':val', $prix);
        }
    

        $cars = $cars->getQuery()->getResult();
        return $cars;
    }


    public function findByCars2(
        ?string $marque, 
        ?int $kilometrage,
        ?int $annee,
        ?int $prix,
    ):array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT * FROM car c
            WHERE c.kilometrage >= :kilometrage AND 
            c.annee >= :annee AND c.prix >= :prix AND c.is_active = :isActive AND marque LIKE :marque
            ';

        $resultSet = $conn->executeQuery($sql, 
            [
                'kilometrage' => $kilometrage,
                'annee' => $annee,
                'prix' => $prix,
                'isActive' => 1,
                'marque' => "%".$marque."%",
            ]
        );

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
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
