<?php
/**
 * QuestionServiceTest.
 */
namespace App\Tests\Service;

use App\Entity\Category;
use App\Entity\Question;
use App\Entity\User;
use App\Repository\QuestionRepository;
use App\Service\QuestionService;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class QuestionServiceTest.
 */
class QuestionServiceTest extends TestCase
{
    private ?QuestionService $questionService;
    private ?QuestionRepository $questionRepository;
    private ?PaginatorInterface $paginator;

    /**
     * SetUp.
     * @return void
     */
    protected function setUp(): void
    {
        $this->questionRepository = $this->getMockBuilder(QuestionRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->paginator = $this->getMockBuilder(PaginatorInterface::class)
            ->getMock();

        $this->questionService = new QuestionService($this->questionRepository, $this->paginator);
    }

    /**
     * testGetPaginatedListReturnsPaginationInterface
     * @return void
     */
    public function testGetPaginatedListReturnsPaginationInterface(): void
    {
        $page = 1;

        $pagination = $this->getMockBuilder(PaginationInterface::class)
            ->getMock();

        $this->paginator->expects($this->once())
            ->method('paginate')
            ->willReturn($pagination);

        $result = $this->questionService->getPaginatedList($page);

        $this->assertInstanceOf(PaginationInterface::class, $result);
    }

    /**
     * estGetPaginatedListByAuthorReturnsPaginationInterface
     * @return void
     */
    public function testGetPaginatedListByAuthorReturnsPaginationInterface(): void
    {
        $page = 1;
        $author = new User();

        $pagination = $this->getMockBuilder(PaginationInterface::class)
            ->getMock();

        $this->paginator->expects($this->once())
            ->method('paginate')
            ->willReturn($pagination);

        $result = $this->questionService->getPaginatedListByAuthor($page, $author);

        $this->assertInstanceOf(PaginationInterface::class, $result);
    }

    /**
     * testGetPaginatedListByCategoryReturnsPaginationInterface
     * @return void
     */
    public function testGetPaginatedListByCategoryReturnsPaginationInterface(): void
    {
        $page = 1;
        $category = new Category();

        $pagination = $this->getMockBuilder(PaginationInterface::class)
            ->getMock();

        $this->paginator->expects($this->once())
            ->method('paginate')
            ->willReturn($pagination);

        $result = $this->questionService->getPaginatedListByCategory($page, $category);

        $this->assertInstanceOf(PaginationInterface::class, $result);
    }

    /**
     * testSave
     * @return void
     */
    public function testSave(): void
    {
        $question = new Question();

        $this->questionRepository->expects($this->once())
            ->method('save')
            ->with($question);

        $this->questionService->save($question);
    }

    /**
     * testDelete.
     * @return void
     */
    public function testDelete(): void
    {
        $question = new Question();

        $this->questionRepository->expects($this->once())
            ->method('delete')
            ->with($question);

        $this->questionService->delete($question);
    }
}
