<?php

namespace App\Repository;

use App\Entity\Bookmark;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bookmark|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bookmark|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bookmark[]    findAll()
 * @method Bookmark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookmarkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bookmark::class);
    }

    public function findByCategoryName($categoryName, $page = 1, $items_per_page = 5)
    {
        $query = $this->createQueryBuilder('b')
            ->innerJoin('b.category', 'c')
            ->andWhere('c.name = :name')
            ->setParameter('name', $categoryName)
            ->orderBy('c.name', 'ASC')
            ->getQuery();

        return $this->pagination($query, $page, $items_per_page);
    }

    public function findByName($name, $page = 1, $items_per_page = 5)
    {
        $query = $this->createQueryBuilder('b')
            ->andWhere('b.name LIKE :name')
            ->setParameter('name', "%$name%")
            ->getQuery();
        
        return $this->pagination($query, $page, $items_per_page);
    }

    private function pagination($query, $page, $items_per_page)
    {
        $paginator = new Paginator($query);

        $paginator->getQuery()
            ->setFirstResult($items_per_page * ($page - 1))
            ->setMaxResults($items_per_page);

        return $paginator;
    }

    // /**
    //  * @return Bookmark[] Returns an array of Bookmark objects
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
    public function findOneBySomeField($value): ?Bookmark
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
