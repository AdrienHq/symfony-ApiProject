<?php

namespace App\Doctrine;

use App\Entity\Books;
use Symfony\Component\Security\Core\Security;

class BooksSetOwnerListener
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(Books $books)
    {
        if($books->getOwner()){
            return;
        }

        if($this->security->getUser()){
            $books->setOwner($this->security->getUser());
        }

    }

}