<?php

namespace Celtic34fr\ContactRendezVous\Repository;

use Celtic34fr\ContactCore\Traits\DbPaginateTrait;
use Celtic34fr\ContactRendezVous\Entity\RendezVous;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RendezVous>
 *
 * @method RendezVous|null find($id, $lockMode = null, $lockVersion = null)
 * @method RendezVous|null findOneBy(array $criteria, array $orderBy = null)
 * @method RendezVous[]    findAll()
 * @method RendezVous[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RendezVousRepository extends ServiceEntityRepository
{
    use DbPaginateTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RendezVous::class);
    }

    public function save(RendezVous $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RendezVous $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findEventsAll(int $currentPage = 1, int $limit = 10): array
    {
        $dJour = $this->findFirstSavedDate();
        return $this->findEventsAllFromDate($currentPage, $limit, $dJour)
    }

    public function findEventsAllFromDate(int $currentPage = 1, int $limit = 10, DateTime $from): array
    {
        $qb = $this->createQueryBuilder("rdv")
            ->where('rdv.time_at >= :from')
            ->orderBy('rdv.time_at', 'ASC')
            ->setParameter('from', $from->format('Y-m-d'))
            ->getQuery();
        return $this->paginateDoctrine($qb, $currentPage, $limit);
    }

    /** recherche première date de rendez-vous */
    public function findFirstSavedDate()
    {
        $qb = $this->createQueryBuilder("rdv")
            ->orderBy('rdv.time_at', 'ASC')
            ->getQuery()
            ->getResult();
        if (!$qb) return false;
        return $qb[0]->getTimeAt();
    }

    //    /**
    //     * @return RendezVous[] Returns an array of RendezVous objects
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

    //    public function findOneBySomeField($value): ?RendezVous
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
