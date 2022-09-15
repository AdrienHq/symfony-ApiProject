<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserDataPersister implements DataPersisterInterface
{
    private $dataPersister;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(DataPersisterInterface $dataPersister, UserPasswordHasherInterface $passwordHasher)
    {
        $this->dataPersister = $dataPersister;
        $this->passwordHasher = $passwordHasher;
    }

    public function supports($data): bool
    {
        return $data instanceof User;
    }

    /** @var User $data */
    public function persist($data)
    {
        if($data->getPlainPassword()){
            $data->setPassword(
                $this->passwordHasher->hashPassword($data, $data->getPlainPassword())
            );
            $data->eraseCredentials();
        }

        $this->dataPersister->persist();
    }

    public function remove($data)
    {
        $this->dataPersister->persist($data);
    }
}