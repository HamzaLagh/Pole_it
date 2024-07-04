<?php

namespace App\Repository;

use App\Pagination\Paginator;
use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }


    public function save(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLatest(int $page = 1): Paginator
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC');
        return (new Paginator($qb))->paginate($page);
    }


    public function deletePost($id)
    {
        $qd = $this->createQueryBuilder('n');
        $qd->delete()
            ->where('n.id = :id')
            ->setParameter('id', $id);
        $query = $qd->getQuery()
            ->execute();
    }


    public function findPosts(string $titre): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.title LIKE :titre')
            ->setParameter('titre', '%' . $titre . '%')
            ->addOrderBy('p.createdAt', 'desc');


        return $qb->getQuery()->getResult();
    }


    public function findArticles(): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }




    // public function findPosts(string $titre): array
    // {
    //     $qb = $this->createQueryBuilder('p')
    //         ->where('p.title LIKE :titre')
    //         ->orWhere('p.content LIKE :titre')
    //         ->setParameter('titre', '%'.$titre.'%')
    //         ->addOrderBy('p.createdAt', 'desc');


    //     return $qb->getQuery()->getResult();
    // }


    //    /**
    //     * @return Post[] Returns an array of Post objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Post
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
