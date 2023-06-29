<?php

namespace App\Tests\Domain\Vehicle;

use App\Entity\Vehicle;
use App\Tests\Support\KernelUtilsTrait;
use App\Tests\Support\Vehicle\VehicleLoaderTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class VehicleEntityTest extends KernelTestCase
{
    use KernelUtilsTrait;
    use VehicleLoaderTrait;

    public function testCreate()
    {
        $options['techData']['type'] = Vehicle\VehicleType::Electric->value;

        $vehicle = $this->createVehicle($options);
        $this->assertInstanceOf(Vehicle::class, $vehicle);
    }
}
