<?php

namespace App\Security\Voter;

use App\Entity\Books;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class BooksVoter extends Voter
{
    private ?Security $security = null;

    public function __construct(?Security $security = null)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject): bool
    {
        $supportsAttribute = in_array($attribute, ['BOOKS_CREATE', 'BOOKS_READ', 'BOOKS_EDIT', 'BOOKS_DELETE']);
        $supportsSubject = $subject instanceof Books;

        return $supportsAttribute && $supportsSubject;
    }

    /**
     * @throws \Exception
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'BOOKS_EDIT':
                if ($this->security->getUser() === $user) {
                    return true;
                }
                if ($this->security->isGranted('ROLE_ADMIN')) {
                    return true;
                }
                return false;
        }
        throw new \Exception(sprintf('Unhandled exception'));
    }
}
