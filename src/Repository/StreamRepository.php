<?php

namespace App\Repository;

use App\Entity\Stream;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Stream>
 */
class StreamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stream::class);
    }

    //this function gets the streams planned for tomorrow
    public function findStreamsForTomorrow(): array
    {
        $demain = new \DateTime('tomorrow');
        $demain->setTime(0, 0, 0);
        
        return $this->createQueryBuilder('s')
            ->where('s.startDate >= :startDate')
            ->andWhere('s.startDate < :endDate')
            ->setParameter('startDate', $demain)
            ->setParameter('endDate', (clone $demain)->modify('+1 day'))
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Stream[] Returns an array of Stream objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Stream
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
