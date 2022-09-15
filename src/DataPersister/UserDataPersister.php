<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserDataPersister implements ContextAwareDataPersisterInterface
{
    private $dataPersister;
    private UserPasswordHasherInterface $passwordHasher;
    private LoggerInterface $logger;

    public function __construct(DataPersisterInterface $dataPersister, UserPasswordHasherInterface $passwordHasher, LoggerInterface $logger)
    {
        $this->dataPersister = $dataPersister;
        $this->passwordHasher = $passwordHasher;
        $this->logger = $logger;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    /** @var User $data */
    public function persist($data, array $context = [])
    {
        if (($context['item_operation_name'] ?? null) === 'put') {
            $this->logger->info(sprintf('User %s is being updated!', $data->getId()));
        }

        if (!$data->getId()) {
            $this->logger->info(sprintf('User %s just registered!', $data->getEmail()));
        }

        if ($data->getPlainPassword()) {
            $data->setPassword(
                $this->passwordHasher->hashPassword($data, $data->getPlainPassword())
            );
            $data->eraseCredentials();
        }

        $this->dataPersister->persist();
    }

    public function remove($data, array $context = [])
    {
        $this->dataPersister->persist($data);
    }
}