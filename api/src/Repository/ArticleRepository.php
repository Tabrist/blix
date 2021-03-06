<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository {

    public const PAGINATOR_PER_PAGE = 2;

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Article::class);
    }

    public function getArticlePaginated(int $page = 0): array {
        $query = $this->createQueryBuilder('a')
                ->setMaxResults(self::PAGINATOR_PER_PAGE)
                ->setFirstResult(self::PAGINATOR_PER_PAGE * --$page)
                ->getQuery()
        ;
        $paginator = new Paginator($query);

        return [
            'meta' => ['all' => $this->count([])],
            'data' => $paginator->getQuery()->getArrayResult()
        ];
    }

}
