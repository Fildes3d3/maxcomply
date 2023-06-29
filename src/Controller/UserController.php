<?php

namespace App\Controller;

use App\Services\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'user_add', methods: ['POST'])]
    public function add(Request $request, UserManager $userManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $user = $userManager->create($data['email'], $data['password'], $data['username']);

        if (null === $user) {
            return $this->json(null, Response::HTTP_NO_CONTENT);
        }

        return $this->json($user, Response::HTTP_CREATED);
    }
}
