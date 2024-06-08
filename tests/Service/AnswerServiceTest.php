<?php
/**
 * AnswerService Test.
 */
namespace App\Tests\Service;

use App\Entity\Answer;
use App\Entity\Question;
use App\Repository\AnswerRepository;
use App\Service\AnswerService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * AnswerServiceTest
 */
class AnswerServiceTest extends TestCase
{
    /**
     * @var AnswerRepository|(AnswerRepository&object&MockObject)|(AnswerRepository&MockObject)|(object&MockObject)|MockObject
     */
    private $answerRepository;
    private $paginator;
    private $entityManager;
    private AnswerService $answerService;

    /**
     * setUp.
     * @return void
     */
    protected function setUp(): void
    {
        $this->answerRepository = $this->createMock(AnswerRepository::class);
        $this->paginator = $this->createMock(PaginatorInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->answerService = new AnswerService(
            $this->answerRepository,
            $this->paginator,
            $this->entityManager
        );
    }

    /**
     * testGetPaginatedList
     * @return void
     */
    public function testGetPaginatedList()
    {
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $pagination = $this->createMock(PaginationInterface::class);

        $this->answerRepository->expects($this->once())
            ->method('queryAll')
            ->willReturn($queryBuilder);

        $this->paginator->expects($this->once())
            ->method('paginate')
            ->with($queryBuilder, 1, AnswerRepository::PAGINATOR_ITEMS_PER_PAGE)
            ->willReturn($pagination);

        $result = $this->answerService->getPaginatedList(1);

        $this->assertSame($pagination, $result);
    }

    /**
     * TestSave.
     * @return void
     */
    public function testSave()
    {
        $answer = new Answer();
        $this->answerRepository->expects($this->once())
            ->method('save')
            ->with($answer);

        $this->answerService->save($answer);
        $this->addToAssertionCount(1);
    }

    /**
     * test Delete
     * @return void
     */
    public function testDelete()
    {
        $answer = new Answer();
        $this->answerRepository->expects($this->once())
            ->method('delete')
            ->with($answer);

        $this->answerService->delete($answer);

        $this->addToAssertionCount(1);
    }

    /**
     * TestFindBy
     * @return void
     */
    public function testFindBy()
    {
        $criteria = ['question' => 1];
        $answers = [new Answer()];
        $this->answerRepository->expects($this->once())
            ->method('findBy')
            ->with($criteria)
            ->willReturn($answers);

        $result = $this->answerService->findBy($criteria);

        $this->assertSame($answers, $result);
    }

    /**
     * testMarkAsBest
     * @return void
     */
    public function testMarkAsBest()
    {
        $answer = $this->createMock(Answer::class);
        $question = $this->createMock(Question::class);

        $answer->method('getQuestion')->willReturn($question);

        $this->answerRepository->expects($this->once())
            ->method('resetBestAnswerForQuestion')
            ->with($question);

        $answer->expects($this->once())
            ->method('setIsBest')
            ->with(true);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->answerService->markAsBest($answer);

        $this->addToAssertionCount(1);
    }

    /**
     * testUnmarkAsBest
     * @return void
     */
    public function testUnmarkAsBest()
    {
        $answer = $this->createMock(Answer::class);

        $answer->expects($this->once())
            ->method('setIsBest')
            ->with(false);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->answerService->unmarkAsBest($answer);

        $this->addToAssertionCount(1);
    }
}
