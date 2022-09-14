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
            'json' => [],
        ]);
        $this->assertResponseStatusCodeSame(401);

        $authenticatedUser = $this->createUserAndLogIn($client, 'bookTest@example.com', "$2y$04$9L4b292zpiqse8QdHmTxceicQXoDERcUOnPcBfjMVqI2k30/mAT.e");
        $otherUser = $this->createUser('newuser@example.com', 'foo');

        $client->request('POST', 'api/books', [
            'json' => [],
        ]);
        $this->assertResponseStatusCodeSame(400);

        $bookData = [
            'title' => 'My test Title',
            'description' => 'My description test',
            'price' => 432
        ];

        $client->request('POST', 'api/books', [
            'json' => $bookData + ['owner'=>'/api/users/'.$otherUser->getId()],
        ]);
        $this->assertResponseStatusCodeSame(201);

        $client->request('POST', 'api/books', [
            'json' => $bookData + ['owner'=>'/api/users/'.$authenticatedUser->getId()],
        ]);
        $this->assertResponseStatusCodeSame(201);
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

    public function testGetBooksCollection()
    {
        $client = self::createClient();
        $user = $this->createUser('user@example.com', 'foo');

        $testBook1 = new Books('My testing book 1');
        $testBook1->setOwner($user);
        $testBook1->setTitle('MyTestBook 1');
        $testBook1->setNumberOfPages(12);
        $testBook1->setAuthor('authorTest');
        $testBook1->setPrice(1000);
        $testBook1->setDescription('mmmm');

        $testBook2 = new Books('My testing book 2');
        $testBook2->setOwner($user);
        $testBook2->setTitle('MyTestBook 2');
        $testBook2->setNumberOfPages(12);
        $testBook2->setAuthor('authorTest');
        $testBook2->setPrice(1000);
        $testBook2->setDescription('mmmm');
        $testBook2->setIsPublished(true);

        $testBook3 = new Books('My testing book 3');
        $testBook3->setOwner($user);
        $testBook3->setTitle('MyTestBook 3');
        $testBook3->setNumberOfPages(12);
        $testBook3->setAuthor('authorTest');
        $testBook3->setPrice(1000);
        $testBook3->setDescription('mmmm');
        $testBook3->setIsPublished(true);

        $em = $this->getEntityManager();
        $em->persist($testBook1);
        $em->persist($testBook2);
        $em->persist($testBook3);
        $em->flush();

        $client->request('GET', '/api/books/');
        $this->assertJsonContains(['hydra:totalItems' => 2]);
    }
}