<?php

namespace App\Repository;

use App\Entity\Outing;
use App\Entity\Search;
use App\Entity\User;
use App\Model\SearchOuting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Outing>
 *
 * @method Outing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Outing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Outing[]    findAll()
 * @method Outing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OutingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Outing::class);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countAfterDate(\DateTime $date): int
    {
        return $this->createQueryBuilder('o')
            ->select('count(o.id)')
            ->andWhere('o.startDate > :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countBeforeDate(\DateTime $date): int
    {
        return $this->createQueryBuilder('o')
            ->select('count(o.id)')
            ->andWhere('o.startDate < :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return Outing[] Returns an array of Outing objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Outing
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findSearch(SearchOuting $searchOuting, UserInterface $userConnected = null): array
    {
        $query = $this->createQueryBuilder('o');

        if ($searchOuting->name) {
            $query->andWhere('o.title LIKE :search')
                ->setParameter('search', "%{$searchOuting->name}%");
        }

        if ($searchOuting->site) {
            $query->join('o.organizer', 'u')
                ->join('u.site', 's')
                ->andWhere('s.id = :site')
                ->setParameter('site', $searchOuting->site->getId());
        }

        if ($searchOuting->startDate) {
            $query->andWhere('o.startDate >= :startDate')
                ->setParameter('startDate', $searchOuting->startDate);
        }

        if ($searchOuting->endDate) {
            $query->andWhere('o.startDate <= :endDate')
                ->setParameter('endDate', $searchOuting->endDate);
        }

        if ($userConnected) {
            if ($searchOuting->isOrganizer) {
                $query->andWhere('o.organizer = :organizer')
                    ->setParameter('organizer', $userConnected);
            }

            if ($searchOuting->isRegistered) {
                $query->join('o.registrations', 'r')
                    ->andWhere('r.participant = :user')
                    ->setParameter('user', $userConnected);
            }

            if ($searchOuting->isNotRegistered) {
                $query->leftJoin('o.registrations', 'r2')
                    ->andWhere('r2.participant != :user')
                    ->setParameter('user', $userConnected);
            }
        }

        if ($searchOuting->isFinished) {
            $query->andWhere('o.startDate < :now')
                ->setParameter('now', new \DateTime());
        }

        $query->andWhere('o.startDate > :now')
            ->setParameter('now', new \DateTime('-1 month'));

        $query->orderBy('o.startDate', 'DESC');

        return $query->getQuery()->getResult();

    }
}
