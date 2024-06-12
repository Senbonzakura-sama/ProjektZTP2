<?php
/**
 * RegistrationServiceTest.
 */

namespace App\Tests\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\RegistrationService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class RegistrationServiceTest.
 */
class RegistrationServiceTest extends TestCase
{
    private RegistrationService $registrationService;
    private UserRepository $userRepositoryMock;

    /**
     * setUp action.
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepositoryMock = $this->createMock(UserRepository::class);
        $passwordHasherMock = $this->createMock(UserPasswordHasherInterface::class);

        $this->registrationService = new RegistrationService($this->userRepositoryMock, $passwordHasherMock);
    }

    /**
     * @return void
     */
    public function testSaveUser()
    {
        $userData = [
            'email' => 'test@example.com',
            'nickname' => 'testuser',
            'password' => 'hashed_password',
        ];
        $user = new User();
        $user->setEmail($userData['email']);
        $user->setNickname($userData['nickname']);
        $user->setPassword($userData['password']);
        $user->setRoles(['ROLE_USER']);

        $this->userRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->equalTo($user));

        $this->registrationService->save($user);
    }
}
