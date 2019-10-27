<?php

namespace App\Repository;

use App\Entity\Booking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    public function findByTimePeriod(\DateTime $startTime, \DateTime $endTime, int $tableNumber)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.start_time = :start_time')
            ->setParameter('start_time', $startTime->format('Y-m-d H:i'))
            ->andWhere('b.end_time = :end_time')
            ->setParameter('end_time', $endTime->format('Y-m-d H:i'))
            ->andWhere('b.table_number = :table_number')
            ->setParameter('table_number', $tableNumber)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getBookingsByDay(\DateTime $day, int $tableNumber)
    {

        return $this->createQueryBuilder('b')
            ->select('b.table_number, DATE(b.start_time) as time')
            ->andWhere('time = :start_time')
            ->setParameter('time', $day->format('Y-m-d'))
            ->andWhere('b.table_number = :table_number')
            ->setParameter('table_number', $tableNumber)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;




        $rsm = new ResultSetMapping();

        // "SELECT * FROM booking b WHERE DATE(b.start_time) = DATE(?) and b.table_number = ? ORDER BY b.id ASC"
        $query = $this->getEntityManager()->createNativeQuery(
                "SELECT * FROM booking b"
        , $rsm)
            ;

        return $query->getResult();
    }

    // /**
    //  * @return Booking[] Returns an array of Booking objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Booking
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
