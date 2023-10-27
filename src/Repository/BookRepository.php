<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function QBfindall(){
        $books = $this->createQueryBuilder("b")
            ->setParameter('param', '%a%')
            ->where('b.title like :param')
            ->orderBy("b.title", "DESC")
            ->getQuery()
            ->getResult();

//            parametre positionnel
//            ->where('b.title LIKE ?1')
//            ->setParameter('1', '%a%')

        return $books;
    }

    public function QBfindallDQL($ref){
       $books = $this->getEntityManager()
           ->createQuery('SELECT p from App\Entity\Book p where p.ref like :ref')
           ->setParameter("ref", $ref)
           ->getResult();
       return($books);
    }

    public function selectByRef($ref){
        $books = $this->createQueryBuilder("b")
            ->where('b.ref LIKE :ref')
            ->orderBy("b.title", "DESC")
            ->setParameter('ref', $ref)
            ->getQuery()
            ->getResult();
        return $books;
    }


//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
