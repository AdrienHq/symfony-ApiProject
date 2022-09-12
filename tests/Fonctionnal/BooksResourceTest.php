<?php

namespace App\Tests\Fonctionnal;

use App\Entity\Books;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class BooksResourceTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;

    public function testCreateBooks()
    {
        $client = self::createClient();

        $client->request('POST', 'api/books', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [],
        ]);
        $this->assertResponseStatusCodeSame(401);

        $this->createUser('bookTest@example.com', "$2y$04$9L4b292zpiqse8QdHmTxceicQXoDERcUOnPcBfjMVqI2k30/mAT.e");

        $this->login($client, 'bookTest@example.com', 'book');
    }

    public function testUpdateBooks()
    {
        $client = self::createClient();
        $user1 = $this->createUser('user1@example.com', '$2y$13$1.SK23LLaCEtDD8gFcHa1uV6PFFqKcS3zKP0y2NpOIRv/pAzjJDs2');
        $user2 = $this->createUser('user2@example.com', '$2y$13$1.SK23LLaCEtDD8gFcHa1uV6PFFqKcS3zKP0y2NpOIRv/pAzjJDs2');

        $testBook = new Books('My testing book');
        $testBook->setTitle('MyTestBook');
        $testBook->setNumberOfPages(12);
        $testBook->setOwner($user1);
        $testBook->setAuthor('authorTest');
        $testBook->setPrice(1000);
        $testBook->setDescription('mmmm');
        $testBook->setIsPublished(true);

        $em = $this->getEntityManager();
        $em->persist($testBook);
        $em->flush();

        $this->logIn($client, 'user2@example.com', "$2y$13$1.SK23LLaCEtDD8gFcHa1uV6PFFqKcS3zKP0y2NpOIRv/pAzjJDs2");
        $client->request('PUT', '/api/books/'.$testBook->getId(), [
            // try to trick security by reassigning to this user
            'json' => ['title' => 'updated', 'owner' => '/api/users/'.$user2->getId()]
        ]);
        $this->assertResponseStatusCodeSame(403, 'only author can updated');

        $this->logIn($client, 'user1@example.com', "$2y$13$1.SK23LLaCEtDD8gFcHa1uV6PFFqKcS3zKP0y2NpOIRv/pAzjJDs2");
        $client->request('PUT', '/api/books/'.$testBook->getId(), [
            'json' => ['title' => 'updated']
        ]);
        $this->assertResponseStatusCodeSame(200);
    }
}