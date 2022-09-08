<?php

namespace App\Tests\Fonctionnal;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class BooksResourceTest extends ApiTestCase
{
    public function testCreateBooks(){
        $this->assertEquals(12,12);
    }
}