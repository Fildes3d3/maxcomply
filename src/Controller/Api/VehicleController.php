<?php

namespace App\Controller\Api;

use App\Entity\Vehicle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('vehicle')]
class VehicleController extends BaseController
{
    #[Route('/makers/{type}', name: 'vehicle_maker_by_type_get', methods: ['GET'])]
    public function getMakersByType(string $type): Response
    {
        $makers = $this->em->getRepository(Vehicle::class)->findVehicleMakersByType($type);

        return $this->json($makers, Response::HTTP_OK);
    }

    #[Route('/tech-data/{id}', name: 'vehicle_tech_data_get', methods: ['GET'])]
    public function getTechData(int $id): Response
    {
        $vehicle = $this->em->getRepository(Vehicle::class)->findOneBy(['id' => $id]);

        return $this->json($vehicle->getTechData(), Response::HTTP_OK);
    }

    #[Route('/tech-data/{param}/update/{id}', name: 'vehicle_tech_data_param_update', requirements: ['param' => '(type|engineData|topSpeed|dimensions)'], methods: ['PATCH'])]
    public function updateTechDataParameter(Request $request, string $param, int $id): Response
    {
        $data = $request->getContent();

        $vehicle = $this->em->getRepository(Vehicle::class)->findOneBy(['id' => $id]);
        $techData = $vehicle->getTechData();
        $techData[$param] = json_decode($data, true)[$param];

        $vehicle->setTechData($techData);

        return $this->json($vehicle, Response::HTTP_OK);
    }
}
