<?php
/**
 * Answer service.
 */

namespace App\Service;

use App\Entity\Answer;
use App\Repository\AnswerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class AnswerService.
 */
class AnswerService implements AnswerServiceInterface
{
    /**
     * Answer Repository.
     */
    private AnswerRepository $answerRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Entity Manager Interface.
     */
    private EntityManagerInterface $entityManager;

    /**
     * Constructor.
     *
     * @param AnswerRepository       $answerRepository Category repository
     * @param PaginatorInterface     $paginator        Paginator
     * @param EntityManagerInterface $entityManager    Entity manager
     */
    public function __construct(AnswerRepository $answerRepository, PaginatorInterface $paginator, EntityManagerInterface $entityManager)
    {
        $this->answerRepository = $answerRepository;
        $this->paginator = $paginator;
        $this->entityManager = $entityManager;
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
            $this->answerRepository->queryAll(),
            $page,
            AnswerRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save Answer.
     *
     * @param mixed $answer
     */
    public function save(Answer $answer): void
    {
        $this->answerRepository->save($answer);
    }

    /**
     * Delete answer.
     *
     * @param mixed $answer
     */
    public function delete(Answer $answer): void
    {
        $this->answerRepository->delete($answer);
    }

    /**
     * Find by question.
     *
     * @param mixed $question
     *
     * @return mixed
     */
    public function findBy(array $question): array
    {
        return $this->answerRepository->findBy($question);
    }

    /**
     * Mark as best.
     *
     * @param mixed $answer
     */
    public function markAsBest(Answer $answer): void
    {
        $question = $answer->getQuestion();
        $this->answerRepository->resetBestAnswerForQuestion($question);
        $answer->setIsBest(true);
        $this->entityManager->flush();
    }

    /**
     * Unmark as best.
     *
     * @param mixed $answer
     */
    public function unmarkAsBest(Answer $answer): void
    {
        $answer->setIsBest(false);
        $this->entityManager->flush();
    }
}
