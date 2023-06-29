<?php

namespace App\Tests\Functional\Controller;

use App\Entity\User;
use App\Entity\Vehicle;
use App\Tests\Support\KernelUtilsTrait;
use App\Tests\Support\User\UserLoaderTrait;
use App\Tests\Support\Vehicle\VehicleLoaderTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    use KernelUtilsTrait;
    use VehicleLoaderTrait;
    use UserLoaderTrait;

    public function testAdd()
    {
        $client = static::createClient();

        $this->purgeTables(static::getContainer(),  [User::class]);

        $client->request(
            'POST',
            '/user/',
            [],
            [],
            [],
            json_encode([
                'username' => 'John Doe',
                'password' => 'test',
                'email' => 'j@doe.com',
            ])
        );

        $responseContent = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertArrayHasKey('username', $responseContent);
        $this->assertArrayHasKey('email', $responseContent);
        $this->assertEquals('John Doe', $responseContent['username']);
        $this->assertEquals('j@doe.com', $responseContent['email']);
    }

    public function testAddExistingUser()
    {
        $client = static::createClient();

        $this->purgeTables(static::getContainer(),  [User::class]);

        $user = $this->createUser();

        $client->request(
            'POST',
            '/user/',
            [],
            [],
            [],
            json_encode([
                'username' => $user->getUsername(),
                'password' => $user->getPassword(),
                'email' => $user->getEmail(),
            ])
        );

        $this->assertResponseStatusCodeSame(204);
    }
}
