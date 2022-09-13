<?php

namespace App\Tests\Fonctionnal;

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

        $this->logIn($client,'testadh@example.com','testadh');
    }

    public function testUpdateUser()
    {
        $client = self::createClient();
        $user = $this->createUserAndLogIn($client, 'testadh@example.com', 'foo');

        $client->request('PUT', 'api/users', [
            'json' => [
                'username' => 'newtestadh'
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'username' => 'newtestadh',
        ]);
    }

}