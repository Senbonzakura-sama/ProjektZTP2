<?php
/**
 * Tag Repository.
 */
namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 *
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in configuration files.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE = 3;

    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select('partial tag.{id, createdAt, updatedAt, title}')
            ->orderBy('tag.updatedAt', 'DESC');
    }

    /**
     * Save entity.
     *
     * @param Tag $tag Tag entity
     */
    public function save(Tag $tag): void
    {
        $this->_em->persist($tag);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Tag $tag Category entity
     */
    public function delete(Tag $tag): void
    {
        $this->_em->remove($tag);
        $this->_em->flush();
    }
    ///**
     //* findOneById.
     //* @param int $id
     //*
     //* @return void
     //*/
    //public function findOneById(int $id)
    //{
    //}
    /**
     * findOneById.
     * @param int $id
     *
     * @return Tag|null
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Tag
    {
        return $this->createQueryBuilder('tag')
            ->andWhere('tag.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }


    ///**
    // * findOneByTitle
    // * @param string $title
    // *
    // * @return void
    // */
    //public function findOneByTitle(string $title)
    //{
    //}
    /**
     * findOneByTitle
     * @param string $title
     *
     * @return Tag|null
     *
     * @throws NonUniqueResultException
     */
    public function findOneByTitle(string $title): ?Tag
    {
        return $this->createQueryBuilder('tag')
            ->andWhere('tag.title = :title')
            ->setParameter('title', $title)
            ->getQuery()
            ->getOneOrNullResult();
    }
    /**
     * Get or create new query builder.
     *
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(): QueryBuilder
    {
        $queryBuilder = null;

        return $queryBuilder ?? $this->createQueryBuilder('tag');
    }
}
