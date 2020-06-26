<?php

namespace App\Repository;

use App\Entity\Video;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Video|null find($id, $lockMode = null, $lockVersion = null)
 * @method Video|null findOneBy(array $criteria, array $orderBy = null)
 * @method Video[]    findAll()
 * @method Video[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Video::class);
        $this->paginator = $paginator;
    }

    public function findByChildIds(array $value, int $page, ?string $sort_method) {

        $sort_method = $sort_method != 'rating' ? $sort_method : 'ASC'; // Temporarily
        $dbquery = $this->createQueryBuilder('v')
            ->andWhere('v.category IN (:val)')
            ->setParameter('val', $value)
            ->orderBy('v.title', $sort_method)
            ->getQuery();

        // 5 is the default value of number of paginated items on website
        $pagination = $this->paginator->paginate($dbquery, $page, 5);

        return $pagination;
    }

    public function findByTitle(string $query, int $page, ?string $sort_method) {

        $sort_method = $sort_method != 'rating' ? $sort_method : 'ASC'; // Temporarily
        $querybilder = $this->createQueryBuilder('v');
        $serachTerms = $this->prepareQuery($query);

        foreach ($serachTerms as $key => $term) {
            $querybilder
                ->orWhere('v.title LIKE :t_' . $key)
                ->setParameter('t_' . $key, '%' . trim($term) . '%');

        }

        $dbquery = $querybilder
            ->orderBy('v.title', $sort_method)
            ->getQuery();

        return $this->paginator->paginate($dbquery, $page, 5);
    }

    private function prepareQuery(string $query): array {

        return explode(' ', $query);
    }

    public function videoDetails($id) {

        return $this->createQueryBuilder('v')
            ->leftJoin('v.comments', 'c')
            ->leftJoin('c.user', 'u')
            ->addSelect('c', 'u')
            ->where('v.id = :id')
            ->setParameter(':id', $id)
            ->orderBy('c.created_at', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    // public function findAllPaginated($page) {

    //     $dbquery = $this->createQueryBuilder('v')
    //         ->getQuery();

    //     // 5 is the default value of number of paginated items on website
    //     $pagination = $this->paginator->paginate($dbquery, $page, 5);

    //     return $pagination;
    // }

    // /**
    //  * @return Video[] Returns an array of Video objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Video
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
