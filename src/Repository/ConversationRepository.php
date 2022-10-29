<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Conversation;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Conversation>
 *
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    public function save(Conversation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Conversation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Conversation[]
     */
    public function findConvsOfUser(User $user, int $limit = 0, int $offset = 0): array
    {
        $qb = $this->createQueryBuilder('c');
        $qb->join('c.users', 'users', 'WITH', $qb->expr()->in('users', $user->getId()));
        
        if ($limit > 0) {
            $qb->setMaxResults($limit);
        }

        $qb->orderBy('c.updated_at', 'DESC')
            ->setFirstResult($offset)
        ;

        return $qb->getQuery()->getResult();
    }

        /**
     * @return string
     */
    public function countConvsOfUser(User $user): string
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('count(c.id)');
        $qb->join('c.users', 'users', 'WITH', $qb->expr()->in('users', $user->getId()));
        
        return $qb->getQuery()->getSingleScalarResult();
    }

//    /**
//     * @return Conversation[] Returns an array of Conversation objects
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

//    public function findOneBySomeField($value): ?Conversation
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
