<?php

namespace Celtic34fr\ContactRendezVous\Repository;

use Celtic34fr\CalendarCore\Entity\Organizer;
use Celtic34fr\ContactCore\Service\ExtensionConfig;
use Celtic34fr\ContactRendezVous\Entity\EventRdv;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<EventRdv>
 *
 * @method EventRdv|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventRdv|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventRdv[]    findAll()
 * @method EventRdv[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRdvRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private ExtensionConfig $extConfig)
    {
        parent::__construct($registry, EventRdv::class);
    }

    public function save(EventRdv $entity, bool $flush = false): void
    {
        $calEvent = $entity->getCalEvent();
        if ($calEvent && $calEvent->emptyOrganizer()) {
            $organizer = new Organizer();
            $entreprise = $this->extConfig->get('celtic34fr-contactcore/entreprise');
            $organizer->setFullname($entreprise["designation"]);
            $organizer->setEmail($entreprise['courreil']);
            $calEvent()->setOrganizer($organizer);
            $entity->setCalEvent($calEvent);
        }

        $this->getEntityManager()->persist($calEvent);
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EventRdv $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return EventRdv[] Returns an array of EventRdv objects
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

//    public function findOneBySomeField($value): ?EventRdv
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}