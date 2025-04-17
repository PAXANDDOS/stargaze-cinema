<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\HallRepository;
use App\Service\AuthService;
use App\Service\HallService;
use App\Validator\HallValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'halls.')]
class HallController extends AbstractController
{
    public function __construct(
        private AuthService $authService,
        private HallService $hallService,
        private HallValidator $hallValidator,
        private HallRepository $hallRepository
    ) {
    }

    #[Route('/halls', name: 'get', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $halls = $this->hallRepository->findAll();

        return new JsonResponse($halls);
    }

    #[Route('/halls', name: 'store', methods: ['POST'])]
    public function store(Request $request): JsonResponse
    {
        if (!$this->authService->authenticatedAsAdmin()) {
            return new JsonResponse(['message' => 'Insufficient access rights.'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        if ('json' === $request->getContentType()) {
            if (!$request = $this->transformJsonBody($request)) {
                return new JsonResponse(
                    ['message' => 'No request body found.'],
                    JsonResponse::HTTP_UNPROCESSABLE_ENTITY
                );
            }
        }

        $params = [
            'name' => $request->get('name'),
            'capacity' => $request->get('capacity'),
            'type' => $request->get('type'),
        ];

        if ($errorResponse = $this->hallValidator->validate($params)) {
            return $errorResponse;
        }

        $hall = $this->hallService->create($params);
        $this->hallService->save($hall);

        return new JsonResponse($hall, JsonResponse::HTTP_CREATED);
    }

    #[Route('/halls/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        if (!$hall = $this->hallRepository->find($id)) {
            return new JsonResponse(['message' => 'No hall found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse($hall);
    }

    #[Route('/halls/{id}', name: 'update', methods: ['PATCH', 'PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        if (!$this->authService->authenticatedAsAdmin()) {
            return new JsonResponse(['message' => 'Insufficient access rights.'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        if ('json' === $request->getContentType()) {
            $request = $this->transformJsonBody($request);
            if (!$request) {
                return new JsonResponse(
                    ['message' => 'No request body found.'],
                    JsonResponse::HTTP_UNPROCESSABLE_ENTITY
                );
            }
        }

        if (!$hall = $this->hallRepository->find($id)) {
            return new JsonResponse(['message' => 'No hall found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $params = [
            'name' => $request->get('name'),
            'capacity' => $request->get('capacity'),
            'type' => $request->get('type'),
        ];

        if ($errorResponse = $this->hallValidator->validate($params, true)) {
            return $errorResponse;
        }

        $hall = $this->hallService->create($params, $hall);
        $this->hallService->save($hall);

        return new JsonResponse($hall, JsonResponse::HTTP_CREATED);
    }

    #[Route('/halls/{id}', name: 'destroy', methods: ['DELETE'])]
    public function destroy(int $id): JsonResponse
    {
        if (!$this->authService->authenticatedAsAdmin()) {
            return new JsonResponse(['message' => 'Insufficient access rights.'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        if (!$hall = $this->hallRepository->find($id)) {
            return new JsonResponse(['message' => 'No hall found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->hallService->delete($hall);

        return new JsonResponse(status: JsonResponse::HTTP_NO_CONTENT);
    }
}
