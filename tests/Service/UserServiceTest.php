<?php
/**
 * UserServiceTest.php.
 */

namespace App\Tests\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * UserServiceTest.
 */
class UserServiceTest extends TestCase
{
    private UserRepository|MockObject $userRepository;

    private PaginatorInterface|MockObject $paginator;

    private UserService $userService;

    /**
     * setUp action.
     *
     * @return void void
     */
    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->paginator = $this->createMock(PaginatorInterface::class);
        $this->userService = new UserService($this->userRepository, $this->paginator);
    }

    /**
     * testGetPaginatedList.
     *
     * @return void void
     */
    public function testGetPaginatedList()
    {
        $page = 1;
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $expectedPagination = $this->createMock(PaginationInterface::class);

        $this->userRepository->expects($this->once())
            ->method('queryAll')
            ->willReturn($queryBuilder);

        $this->paginator->expects($this->once())
            ->method('paginate')
            ->with(
                $queryBuilder,
                $page,
                UserRepository::PAGINATOR_ITEMS_PER_PAGE
            )
            ->willReturn($expectedPagination);

        $pagination = $this->userService->getPaginatedList($page);

        $this->assertSame($expectedPagination, $pagination);
    }

    /**
     * testSave.
     *
     * @return void void
     */
    public function testSave()
    {
        $user = new User();

        $this->userRepository->expects($this->once())
            ->method('save')
            ->with($user, true);

        $this->userService->save($user);
    }

    /**
     * testDelete.
     *
     * @return void void
     */
    public function testDelete()
    {
        $user = new User();

        $this->userRepository->expects($this->once())
            ->method('remove')
            ->with($user, true);

        $this->userService->delete($user);
    }

    /**
     * testCanBeDeleted.
     *
     * @return void void
     */
    public function testCanBeDeleted()
    {
        $user = new User();

        $this->userRepository->expects($this->once())
        ->method('countByUser')
        ->with($user)
        ->willReturn(0);

        $result = $this->userService->canBeDeleted($user);

        $this->assertTrue($result);
    }

    /**
     * testCanBeDeletedWithDependencies.
     *
     * @return void void
     */
    public function testCanBeDeletedWithDependencies()
    {
        $user = new User();

        $this->userRepository->expects($this->once())
            ->method('countByUser')
            ->with($user)
            ->willReturn(1);

        $result = $this->userService->canBeDeleted($user);

        $this->assertFalse($result);
    }

    /**
     * testCanBeDeletedWithNoResultException.
     *
     * @return void void
     */
    public function testCanBeDeletedWithNoResultException()
    {
        $user = new User();

        $this->userRepository->expects($this->once())
            ->method('countByUser')
            ->with($user)
            ->willThrowException(new NoResultException());

        $result = $this->userService->canBeDeleted($user);

        $this->assertFalse($result);
    }

    /**
     * testCanBeDeletedWithNonUniqueResultException.
     *
     * @return void void
     */
    public function testCanBeDeletedWithNonUniqueResultException()
    {
        $user = new User();

        $this->userRepository->expects($this->once())
            ->method('countByUser')
            ->with($user)
            ->willThrowException(new NonUniqueResultException());

        $result = $this->userService->canBeDeleted($user);

        $this->assertFalse($result);
    }
}
