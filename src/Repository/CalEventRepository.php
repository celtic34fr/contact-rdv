<?php

namespace Celtic34fr\ContactRendezVous\Repository;

use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Celtic34fr\ContactCore\Entity\Parameter;
use Celtic34fr\ContactCore\Enum\EventEnums;
use Celtic34fr\ContactRendezVous\Entity\CalEvent;
use Celtic34fr\ContactCore\Traits\DbPaginateTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<CalEvent>
 *
 * @method CalEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method CalEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method CalEvent[]    findAll()
 * @method CalEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalEventRepository extends ServiceEntityRepository
{
    use DbPaginateTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CalEvent::class);
    }

    public function save(CalEvent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CalEvent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllPaginate(int $currentPage = 1, int $limit =10, string $type = "array"): array
    {
        if (strtoupper($type) != "ARRAY" || strtoupper($type) != "JSON") $type = "ARRAY";
        $qb = $this->createQueryBuilder("rdv")
            ->orderBy('rdv.time_at', 'ASC')
            ->getQuery();
        $results = $this->paginateDoctrine($qb, $currentPage, $limit);
        return $this->formatEvents($results, $type);
    }

    public function findAllPaginateFromDate(int $currentPage = 1, int $limit = 10,
            DateTime $from = null, string $type = "array"): array
    {
        if (!$from) $from = new DateTime('now');
        $type = strtoupper($type);
        if ($type != "ARRAY" && $type != "JSON") $type = "ARRAY";
        $qb = $this->createQueryBuilder("rdv")
            ->where('rdv.time_at >= :from')
            ->orderBy('rdv.time_at', 'ASC')
            ->setParameter('from', $from->format("Y-m-d"))
            ->getQuery()
        ;
        $results = $this->paginateDoctrine($qb, $currentPage, $limit);
        return $this->formatEvents($results, $type);
    }

    public function findEventsByCategory(Parameter $category)
    {
        return $this->createQueryBuilder('ce')
            ->where("ce.nature = :nature")
            ->setParameter('nature', $category)
            ->orderBy('start_at', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllRendezVousPaginate(int $currentPage = 1, int $limit = 10, DateTime $from = null,
        string $type = "array")
    {
        if (!$from) $from = new DateTime('now');
        $type = strtoupper($type);
        if ($type != "ARRAY" && $type != "JSON") $type = "ARRAY";
        $qb = $this->createQueryBuilder('rdv')
            ->where('rdv.nature = :nature')
            ->andWhere('rdv.time_at >= :from')
            ->setParameter('nature', EventEnums::ContactTel->_toString())
            ->setParameter('from', $from->format("Y-m-d"))
            ->getQuery()
            ;
        $results = $this->paginateDoctrine($qb, $currentPage, $limit);
        return $this->formatEvents($results, $type);
    }

//    /**
//     * @return CalEvent[] Returns an array of CalEvent objects
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

//    public function findOneBySomeField($value): ?CalEvent
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    private function formatEvents(array $events, string $type)
    {
        switch ($type) {
            case "ARRAY":
                continue;
                break;
            case "JSON":
                $events['datas'] = json_encode($events['datas']);
                break;
        }
        return $events;

    }
}
