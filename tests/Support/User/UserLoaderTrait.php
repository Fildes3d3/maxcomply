<?php

namespace App\Tests\Support\User;


use App\Entity\User;

trait UserLoaderTrait
{
    private function createUser(?array $options = []): User
    {
        $fixtureManager = $this->getFixtureManager(static::getContainer());

        return $fixtureManager->createUser($options);
    }
}
