<?php
/**
 * User Repository.
 */

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public const PAGINATOR_ITEMS_PER_PAGE = 5;

    /**
     * Constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select('partial user.{id, email, roles, password, nickname}')
            ->orderBy('user.id', 'ASC');
    }

    /**
     * Save action.
     * @param User $entity
     * @param bool $flush
     *
     * @return void
     */
    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Remove action.
     * @param User $entity
     * @param bool $flush
     *
     * @return void
     */
    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * upgradePassword action.
     * @param PasswordAuthenticatedUserInterface $user
     * @param string                             $newHashedPassword
     *
     * @return void
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

    /**
     * countByUser.
     * @param User $user
     *
     * @return int
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countByUser(User $user): int
    {
        $qb = $this->getOrCreateQueryBuilder();

        return $qb->select($qb->expr()->countDistinct('question.id'))
            ->from('App\Entity\Question', 'question')
            ->where('question.author = :author')
            ->setParameter(':author', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    // dodane
    /**
     * findOneByEmail.
     * @param string $email
     *
     * @return User|null
     *
     * @throws NonUniqueResultException
     */
    public function findOneByEmail(string $email): ?User
    {
        return $this->createQueryBuilder('user')
            ->andWhere('user.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // dodane
    /**
     * Get or create new query builder.
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(): QueryBuilder
    {
        $queryBuilder = null;

        return $queryBuilder ?? $this->createQueryBuilder('user');
    }
}
