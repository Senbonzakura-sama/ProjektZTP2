<?php
/**
 * Question service.
 */

namespace App\Service;

use App\Entity\Question;
use App\Entity\User;
use App\Entity\Category;
use App\Repository\QuestionRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class QuestionService.
 */
class QuestionService implements QuestionServiceInterface
{
    /**
     * Question repository.
     */
    private QuestionRepository $questionRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param QuestionRepository $questionRepository Question repository
     * @param PaginatorInterface $paginator          Paginator
     */
    public function __construct(QuestionRepository $questionRepository, PaginatorInterface $paginator)
    {
        $this->questionRepository = $questionRepository;
        $this->paginator = $paginator;
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->questionRepository->queryAll(),
            $page,
            QuestionRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Get paginated list by author.
     * @param int  $page
     *
     * @param User $author
     *
     * @return PaginationInterface
     */
    public function getPaginatedListByAuthor(int $page, User $author): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->questionRepository->queryByAuthor($author),
            $page,
            QuestionRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Get paginated list by Category.
     *
     * @param int      $page     Page number
     * @param Category $category Category
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedListByCategory(int $page, Category $category): PaginationInterface
    {

        return $this->paginator->paginate(
            $this->questionRepository->queryByCategory($category),
            $page,
            QuestionRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Question $question Question entity
     */
    public function save(Question $question): void
    {
        $this->questionRepository->save($question);
    }

    /**
     * Delete entity.
     *
     * @param Question $question Question entity
     */
    public function delete(Question $question): void
    {
        $this->questionRepository->delete($question);
    }
}
