<?php

namespace App\Tests\Support\Vehicle;

use App\Entity\Vehicle;

trait VehicleLoaderTrait
{
    private function createVehicle(array $options): Vehicle
    {
        $fixtureManager = $this->getFixtureManager(static::getContainer());

        return $fixtureManager->createVehicle($options);
    }
}
