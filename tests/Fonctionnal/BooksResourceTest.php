<?php

namespace App\Tests\Fonctionnal;

use App\Entity\Books;
use App\Test\CustomApiTestCase;
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
        $user1 = $this->createUser('user1@example.com', 'foo');
        $user2 = $this->createUser('user2@example.com', 'foo');

        $testBook = new Books('My testing book');
        $testBook->setOwner($user1);
        $testBook->setPrice(1000);
        $testBook->setDescription('mmmm');
        $testBook->setIsPublished(true);

        $em = $this->getEntityManager();
        $em->persist($testBook);
        $em->flush();

        $this->logIn($client, 'user2@example.com', 'foo');
        $client->request('PUT', '/api/books/'.$testBook->getId(), [
            // try to trick security by reassigning to this user
            'json' => ['title' => 'updated', 'owner' => '/api/users/'.$user2->getId()]
        ]);
        $this->assertResponseStatusCodeSame(403, 'only author can updated');

        $this->logIn($client, 'user1@example.com', 'foo');
        $client->request('PUT', '/api/books/'.$testBook->getId(), [
            'json' => ['title' => 'updated']
        ]);
        $this->assertResponseStatusCodeSame(200);
    }
}