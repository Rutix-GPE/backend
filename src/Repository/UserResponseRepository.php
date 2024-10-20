<?php

namespace App\Repository;

use App\Entity\UserResponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserResponse>
 */
class UserResponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserResponse::class);
    }

    public function add(UserResponse $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserResponse $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findUserResponseByUserAndQuestion(int $userId, int $questionId): ?UserResponse
    {
        return $this->createQueryBuilder('ur')
            ->andWhere('ur.user = :userId')
            ->andWhere('ur.question = :questionId')
            ->setParameter('userId', $userId)
            ->setParameter('questionId', $questionId)
            ->getQuery()
            ->getOneOrNullResult(); // Renvoie null si aucune réponse n'est trouvée
    }
    
//    /**
//     * @return UserResponse[] Returns an array of UserResponse objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserResponse
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
