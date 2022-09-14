<?php

namespace App\Tests\Fonctionnal;

use App\Entity\User;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class UserResourceTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;

    public function testCreateUser()
    {
        $client = self::createClient();

        $client->request('POST', 'api/users', [
            'json' => [
                'email' => 'testadh@example.com',
                'username' => 'testadh',
                'password' => 'testadh',
            ]
        ]);
        $this->assertResponseStatusCodeSame(201);

        $this->logIn($client, 'testadh@example.com', 'testadh');
    }

    public function testUpdateUser()
    {
        $client = self::createClient();
        $user = $this->createUserAndLogIn($client, 'testadh@example.com', 'foo');

        $client->request('PUT', 'api/users', [
            'json' => [
                'username' => 'newtestadh',
                'roles' => ['ROLE_ADMIN'],
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'username' => 'newtestadh',
        ]);

        $em = $this->getEntityManager();
        /** @var User $user */
        $user = $em->getRepository(User::class)->find($user->getId());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
    }

    public function testGetUser()
    {
        $client = self::createClient();
        $user = $this->createUser('testadh@example.com', 'foo');
        $this->createUserAndLogIn($client, 'testadhother@example.com', 'foo' );

        $user->setPhoneNumber('0448123456');
        $em = $this->getEntityManager();
        $em->flush();

        $client->request('GET', 'api/users' . $user->getId());
        $this->assertJsonContains([
            'username' => 'testadh',
        ]);

        $data = $client->getResponse()->toArray();
        $this->assertArrayNotHasKey('phoneNumber', $data);

        $user = $em->getRepository(User::class)->find($user->getId());
        $user->setRoles(['ROLE_ADMIN']);
        $em->flush();
        $this->logIn($client, 'testadh@example.com', 'foo');

        $client->request('GET', 'api/users' . $user->getId());
        $this->assertJsonContains([
            'phoneNumber' => '0448123456',
        ]);
    }

}