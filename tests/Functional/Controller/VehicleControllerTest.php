<?php

namespace App\Tests\Functional\Controller;

use App\Entity\User;
use App\Entity\Vehicle;
use App\Tests\Support\KernelUtilsTrait;
use App\Tests\Support\User\UserLoaderTrait;
use App\Tests\Support\Vehicle\VehicleLoaderTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VehicleControllerTest extends WebTestCase
{
    use KernelUtilsTrait;
    use VehicleLoaderTrait;
    use UserLoaderTrait;

    public function testGetUnauthorized()
    {
        $client = static::createClient();

        $this->purgeTables(static::getContainer(),  [Vehicle::class, User::class]);

        $optionsElectricVehicle['make'] = 'Tesla';
        $optionsElectricVehicle['techData']['type'] = Vehicle\VehicleType::Electric->value;

        $this->createVehicle($optionsElectricVehicle);

        $client->request(
            'GET',
            '/api/v1/vehicle/makers/' . Vehicle\VehicleType::Electric->value,
        );

        $this->assertResponseStatusCodeSame(401, 'JWT Token not found');
    }

    public function testGetVehicleMakersByType()
    {
        $client = static::createClient();
        $jwtManager = $this->getJWTManager(static::getContainer());

        $this->purgeTables(static::getContainer(),  [Vehicle::class, User::class]);

        $optionsElectricVehicle['make'] = 'Tesla';
        $optionsElectricVehicle['techData']['type'] = Vehicle\VehicleType::Electric->value;
        $optionsHybridVehicle['techData']['type'] = Vehicle\VehicleType::Hybrid->value;
        $optionsIntCombustionVehicle['techData']['type'] = Vehicle\VehicleType::InternalCombustion->value;

        $this->createVehicle($optionsElectricVehicle);
        $this->createVehicle($optionsElectricVehicle);
        $this->createVehicle($optionsHybridVehicle);
        $this->createVehicle($optionsHybridVehicle);
        $this->createVehicle($optionsIntCombustionVehicle);

        $jwtToken = $jwtManager->create($this->createUser());

        $client->request(
            'GET',
            '/api/v1/vehicle/makers/' . Vehicle\VehicleType::Electric->value,
            [],
            [],
            [
                'HTTP_authorization' => 'Bearer ' . $jwtToken,
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $responseContent = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertCount(1, $responseContent);
        $this->assertEquals('Tesla', $responseContent[0]['make']);


        $client->request(
            'GET',
            '/api/v1/vehicle/makers/' . Vehicle\VehicleType::Hybrid->value,
            [],
            [],
            [
                'HTTP_authorization' => 'Bearer ' . $jwtToken,
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertCount(2, json_decode($client->getResponse()->getContent()));

        $client->request(
            'GET',
            '/api/v1/vehicle/makers/' . Vehicle\VehicleType::InternalCombustion->value,
            [],
            [],
            [
                'HTTP_authorization' => 'Bearer ' . $jwtToken,
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertCount(1, json_decode($client->getResponse()->getContent()));
    }

    public function testGetVehicleTechData()
    {
        $client = static::createClient();
        $jwtManager = $this->getJWTManager(static::getContainer());

        $this->purgeTables(static::getContainer(),  [Vehicle::class, User::class]);

        $options['techData']['type'] = Vehicle\VehicleType::Electric->value;
        $options['techData']['topSpeed'] = 200;
        $options['techData']['dimensions'] = [
            'width' => 2,
            'length' => 1.8,
            'height' => 1.2,
        ];
        $options['techData']['engineData'] = [
            'displacement' => 3500,
            'power' => 400,
        ];

        $vehicle = $this->createVehicle($options);

        $jwtToken = $jwtManager->create($this->createUser());

        $client->request(
            'GET',
            '/api/v1/vehicle/tech-data/' . $vehicle->getId(),
            [],
            [],
            [
                'HTTP_authorization' => 'Bearer ' . $jwtToken,
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $responseContent = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertArrayHasKey('type', $responseContent);
        $this->assertEquals(
            $options['techData']['type'], $responseContent['type']);
        $this->assertArrayHasKey('topSpeed', $responseContent);
        $this->assertEquals($options['techData']['topSpeed'], $responseContent['topSpeed']);
        $this->assertArrayHasKey('dimensions', $responseContent);
        $this->assertEquals($options['techData']['dimensions']['width'], $responseContent['dimensions']['width']);
        $this->assertEquals($options['techData']['dimensions']['length'], $responseContent['dimensions']['length']);
        $this->assertEquals($options['techData']['dimensions']['height'], $responseContent['dimensions']['height']);
        $this->assertArrayHasKey('engineData', $responseContent);
        $this->assertEquals(
            $options['techData']['engineData']['displacement'],
            $responseContent['engineData']['displacement']
        );
        $this->assertEquals(
            $options['techData']['engineData']['power'],
            $responseContent['engineData']['power']
        );
    }

    public function testUpdateTechDataParameter()
    {
        $client = static::createClient();
        $jwtManager = $this->getJWTManager(static::getContainer());

        $this->purgeTables(static::getContainer(), [Vehicle::class, User::class]);

        $options['techData']['type'] = Vehicle\VehicleType::Electric->value;
        $options['techData']['topSpeed'] = 200;
        $options['techData']['dimensions'] = [
            'width' => 2,
            'length' => 1.8,
            'height' => 1.2,
        ];
        $options['techData']['engineData'] = [
            'displacement' => 3500,
            'power' => 400,
        ];

        $vehicle = $this->createVehicle($options);

        $parameter = 'type';
        $jwtToken = $jwtManager->create($this->createUser());

        $client->request(
            'PATCH',
            '/api/v1/vehicle/tech-data/' . $parameter . '/update/' . $vehicle->getId(),
            [],
            [],
            [
                'HTTP_authorization' => 'Bearer ' . $jwtToken,
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode(['type' => Vehicle\VehicleType::Hybrid->value])
        );

        $responseContent = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertArrayHasKey('techData', $responseContent);
        $this->assertEquals(Vehicle\VehicleType::Hybrid->value, $responseContent['techData']['type']);

        $parameter = 'engineData';

        $client->request(
            'PATCH',
            '/api/v1/vehicle/tech-data/' . $parameter . '/update/' . $vehicle->getId(),
            [],
            [],
            [
                'HTTP_authorization' => 'Bearer ' . $jwtToken,
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                'engineData' => [
                    'displacement' => 1200,
                    'power' => 90,
                ]
            ])
        );

        $responseContent = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertArrayHasKey('techData', $responseContent);

        $this->assertEquals(
            [
                'displacement' => 1200,
                'power' => 90,
            ],
            $responseContent['techData']['engineData']
        );
    }
}
