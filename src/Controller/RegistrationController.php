<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\User\UserManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

readonly class RegistrationController
{
    public function __construct(private UserManager $registrationUserService)
    {
    }

    #[Route('/v1/users', name: 'create_user', methods: ['POST'])]
    public function createUser(Request $request): Response
    {
        try {
            $user = $this->registrationUserService->registerUser($request->toArray());

            return new JsonResponse($user, Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
