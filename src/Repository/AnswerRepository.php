<?php
/**
 * Answer repository.
 */

namespace App\Repository;

use App\Entity\Answer;
use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Answer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Answer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Answer[]    findAll()
 * @method Answer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * BookRepository constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Answer::class);
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->orderBy('answer.updatedAt', 'DESC');
    }

    /**
     * Save record.
     *
     * @param Answer $answer Answer entity
     */
    public function save(Answer $answer): void
    {
        $this->_em->persist($answer);
        $this->_em->flush();
    }

    /**
     * Delete record.
     *
     * @param Answer $answer Answer entity
     *
     */
    public function delete(Answer $answer): void
    {
        $this->_em->remove($answer);
        $this->_em->flush();
    }
    /**
     * resetBestAnswerForQuestion
     * @param Question $question
     *
     * @return void
     */
    public function resetBestAnswerForQuestion(Question $question): void
    {
        $answers = $this->findBy(['question' => $question, 'isBest' => true]);
        foreach ($answers as $answer) {
            $answer->setIsBest(false);
        }
        $this->_em->flush();
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

        return $queryBuilder ?? $this->createQueryBuilder('answer');
    }
}
