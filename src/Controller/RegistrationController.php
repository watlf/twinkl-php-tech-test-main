<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController
{
    #[Route('/v1/users', name: 'create_user', methods: ['POST'])]
    public function createUser(Request $request): Response
    {
        return new JsonResponse();
    }
}