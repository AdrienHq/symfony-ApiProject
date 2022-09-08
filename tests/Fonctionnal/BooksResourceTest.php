<?php

namespace App\Tests\Fonctionnal;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class BooksResourceTest extends ApiTestCase
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

        $user = new User();
        $user->setEmail('bookTest@example.com');
        $user->setUsername('bookTest');
        $user->setPassword('$2y$13$2nJIm9u7Itd1xKuBjAcAIe5g0JeJE2nnD3IcAnyK3P1wrEb/MTJ9S');

        $em = self::getContainer()->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();

        $client->request('POST', '/login', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'book@example.com',
                'password' => 'book'
            ],
        ]);
        $this->assertResponseStatusCodeSame(401);
    }
}