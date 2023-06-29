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

        return $this->json(\array_slice($vehicle->getTechData(), 0, 10), Response::HTTP_OK);
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

    #[Route('/', name: 'vehicle_list', methods: ['GET'])]
    public function list()
    {
        //TODO method implementation
    }

    #[Route('/{id}', name: 'vehicle_self', methods: ['GET'])]
    public function self()
    {
        //TODO method implementation
    }

    #[Route('/', name: 'vehicle_add', methods: ['POST'])]
    public function add()
    {
        //TODO method implementation
    }

    #[Route('/{id}', name: 'vehicle_update', methods: ['PUT'])]
    public function update()
    {
        //TODO method implementation
    }

    #[Route('/{id}', name: 'vehicle_remove', methods: ['DELETE'])]
    public function remove()
    {
        //TODO method implementation
    }
}
