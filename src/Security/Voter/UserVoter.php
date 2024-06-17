<?php
/**
 * User Voter.
 */

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserVoter.
 */
class UserVoter extends Voter
{
    public const EDIT = 'EDIT';

    public const DELETE = 'DELETE';

    private Security $security;

    /**
     * Constructor.
     *
     * @param Security<string, mixed> $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * supports.
     *
     * @param mixed  $attribute
     * @param string $subject
     *
     * @return mixed
     */
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof User;
    }

    /**
     * voteOnAttribute.
     *
     * @param mixed                         $attribute
     * @param string                        $subject
     * @param TokenInterface<string, mixed> $token
     *
     * @return mixed
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var User $targetUser */
        $targetUser = $subject;

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return $targetUser->getId() === $user->getId();
    }
}
