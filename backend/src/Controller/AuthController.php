<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\UserService;
use App\Validator\UserValidator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/auth', name: 'auth.')]
class AuthController extends AbstractController
{
    public function __construct(
        private UserService $userService,
        private UserRepository $userRepository,
        private UserValidator $userValidator
    ) {
    }

    #[Route('/signup', name: 'signup', methods: ['POST'])]
    public function signUp(Request $request): JsonResponse
    {
        $request = $this->transformJsonBody($request);
        if (!$request) {
            return new JsonResponse(['message' => 'No request body found.'], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $params = [
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'passwordConfirmation' => $request->get('password_confirmation'),
        ];

        if ($errorResponse = $this->userValidator->validateSignUp($params)) {
            return $errorResponse;
        }

        if ($this->userRepository->findOneBy(['email' => $params['email']])) {
            return new JsonResponse(['message' => 'This email is already taken.'], JsonResponse::HTTP_CONFLICT);
        }
        if ($params['password'] !== $params['passwordConfirmation']) {
            return new JsonResponse(['message' => 'Password did not match.'], JsonResponse::HTTP_CONFLICT);
        }

        $user = $this->userService->create($params);
        $this->userService->save($user);

        return new JsonResponse($user, JsonResponse::HTTP_CREATED);
    }

    #[Route('/signin', name: 'signin', methods: ['POST'])]
    public function signIn(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $JWTManager
    ): JsonResponse {
        $request = $this->transformJsonBody($request);
        if (!$request) {
            return new JsonResponse(['message' => 'No request body found.'], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $params = [
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ];

        if ($errorResponse = $this->userValidator->validateSignIn($params)) {
            return $errorResponse;
        }

        if (!$user = $this->userRepository->findOneBy(['email' => $params['email']])) {
            return new JsonResponse(['message' => 'Invalid credentials.'], JsonResponse::HTTP_CONFLICT);
        }

        if (!$passwordHasher->isPasswordValid($user, $params['password'])) {
            return new JsonResponse(['message' => 'Incorrect password.'], JsonResponse::HTTP_CONFLICT);
        }

        $token = $JWTManager->create($user);
        $ttl = \Namshi\JOSE\JWS::load($token)->getPayload()['exp'];

        return new JsonResponse([
            'token' => [
                'value' => $token,
                'ttl' => $ttl,
            ],
            'user' => $user,
        ], JsonResponse::HTTP_OK);
    }
}
