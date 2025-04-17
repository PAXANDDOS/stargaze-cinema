<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\DirectorRepository;
use App\Service\AuthService;
use App\Service\DirectorService;
use App\Validator\DirectorValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'directors.')]
class DirectorController extends AbstractController
{
    public function __construct(
        private AuthService $authService,
        private DirectorService $directorService,
        private DirectorValidator $directorValidator,
        private DirectorRepository $directorRepository
    ) {
    }

    #[Route('/directors', name: 'get', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $directors = $this->directorRepository->findAll();

        return new JsonResponse($directors);
    }

    #[Route('/directors', name: 'store', methods: ['POST'])]
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

        $params = ['name' => $request->get('name')];

        if ($errorResponse = $this->directorValidator->validate($params)) {
            return $errorResponse;
        }

        $director = $this->directorService->create($params);
        $this->directorService->save($director);

        return new JsonResponse($director, JsonResponse::HTTP_CREATED);
    }

    #[Route('/directors/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        if (!$director = $this->directorRepository->find($id)) {
            return new JsonResponse(['message' => 'No director found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse($director);
    }

    #[Route('/directors/{id}', name: 'update', methods: ['PATCH', 'PUT'])]
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

        if (!$director = $this->directorRepository->find($id)) {
            return new JsonResponse(['message' => 'No director found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $params = ['name' => $request->get('name')];

        if ($errorResponse = $this->directorValidator->validate($params, true)) {
            return $errorResponse;
        }

        $director = $this->directorService->create($params, $director);
        $this->directorService->save($director);

        return new JsonResponse($director, JsonResponse::HTTP_CREATED);
    }

    #[Route('/directors/{id}', name: 'destroy', methods: ['DELETE'])]
    public function destroy(int $id): JsonResponse
    {
        if (!$this->authService->authenticatedAsAdmin()) {
            return new JsonResponse(['message' => 'Insufficient access rights.'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        if (!$director = $this->directorRepository->find($id)) {
            return new JsonResponse(['message' => 'No director found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->directorService->delete($director);

        return new JsonResponse(status: JsonResponse::HTTP_NO_CONTENT);
    }
}
