<?php
/**
 * Answer service interface.
 */

namespace App\Service;

use App\Entity\Answer;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface AnswerServiceInterface.
 */
interface AnswerServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * markAsBest.
     * @param Answer $answer
     *
     * @return void
     */
    public function markAsBest(Answer $answer): void;

    /**
     * Save entity.
     * @param Answer $answer
     *
     * @return void
     */
    public function save(Answer $answer): void;

    /**
     * Delete entity.
     * @param Answer $answer
     *
     * @return void
     */
    public function delete(Answer $answer): void;

    /**
     * unmarkAsBest.
     * @param Answer $answer
     *
     * @return void
     */
    public function unmarkAsBest(Answer $answer): void;
}
