<?php

namespace App\Tests\Fonctionnal;

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

//    public function testCreateCheeseListing()
//    {
//        $client = self::createClient();
//        $client->request('POST', '/api/books', [
//            'json' => [],
//        ]);
//        $this->assertResponseStatusCodeSame(401);
//
//        $authenticatedUser = $this->createUserAndLogIn($client, 'cheeseplease@example.com', 'foo');
//        $otherUser = $this->createUser('otheruser@example.com', 'foo');
//
//        $cheesyData = [
//            'title' => 'Mystery cheese... kinda green',
//            'description' => 'What mysteries does it hold?',
//            'price' => 5000
//        ];
//
//        $client->request('POST', '/api/cheeses', [
//            'json' => $cheesyData,
//        ]);
//        $this->assertResponseStatusCodeSame(201);
//
//        $client->request('POST', '/api/cheeses', [
//            'json' => $cheesyData + ['owner' => '/api/users/'.$otherUser->getId()],
//        ]);
//        $this->assertResponseStatusCodeSame(400, 'not passing the correct owner');
//
//        $client->request('POST', '/api/cheeses', [
//            'json' => $cheesyData + ['owner' => '/api/users/'.$authenticatedUser->getId()],
//        ]);
//        $this->assertResponseStatusCodeSame(201);
//    }
}