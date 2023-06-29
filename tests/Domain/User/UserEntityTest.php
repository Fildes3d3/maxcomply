<?php

namespace App\Tests\Domain\User;

use App\Entity\User;
use App\Tests\Support\KernelUtilsTrait;
use App\Tests\Support\User\UserLoaderTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserEntityTest extends KernelTestCase
{
    use KernelUtilsTrait;
    use UserLoaderTrait;

    public function testCreate()
    {
        $user = $this->createUser();
        $this->assertInstanceOf(User::class, $user);
    }
}
