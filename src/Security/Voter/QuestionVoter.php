<?php
/**
 * Question voter.
 */

namespace App\Security\Voter;

use App\Entity\Question as QuestionAlias;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class QuestionVoter.
 */
class QuestionVoter extends Voter
{
    /**
     * Edit permission.
     *
     * @const string
     */
    private const EDIT = 'EDIT';
    /**
     * Delete permission.
     *
     * @const string
     */
    private const DELETE = 'DELETE';

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed  $subject   The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool Result
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof QuestionAlias;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string         $attribute Permission name
     * @param mixed          $subject   Object
     * @param TokenInterface $token     Security token
     *
     * @return bool Vote result
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }
        if (!$subject instanceof QuestionAlias) {
            return false;
        }

        return match ($attribute) {
            self::EDIT => $this->canEdit($subject, $user),
            self::DELETE => $this->canDelete($subject, $user),
            default => false,
        };
    }

    /**
     * Checks if user can edit question.
     *
     * @param QuestionAlias $question Question entity
     * @param UserInterface $user     User
     *
     * @return bool Result
     */
    private function canEdit(QuestionAlias $question, UserInterface $user): bool
    {
        return $this->isAdmin($user) || $question->getAuthor() === $user;
    }

    /**
     * canDelete action.
     *
     * @param mixed $question
     * @param mixed $user
     *
     * @return mixed
     */
    private function canDelete(QuestionAlias $question, UserInterface $user): bool
    {
        return $this->isAdmin($user) || $question->getAuthor() === $user;
    }

    /**
     * Checks if user has admin role.
     *
     * @param UserInterface $user User
     *
     * @return bool Result
     */
    private function isAdmin(UserInterface $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles(), true);
    }
}
