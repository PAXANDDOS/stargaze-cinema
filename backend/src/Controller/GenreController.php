<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\GenreRepository;
use App\Service\AuthService;
use App\Service\GenreService;
use App\Validator\GenreValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'genres.')]
class GenreController extends AbstractController
{
    public function __construct(
        private AuthService $authService,
        private GenreService $genreService,
        private GenreValidator $genreValidator,
        private GenreRepository $genreRepository
    ) {
    }

    #[Route('/genres', name: 'get', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $genres = $this->genreRepository->findAll();

        return new JsonResponse($genres);
    }

    #[Route('/genres', name: 'store', methods: ['POST'])]
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

        if ($errorResponse = $this->genreValidator->validate($params)) {
            return $errorResponse;
        }

        $genre = $this->genreService->create($params);
        $this->genreService->save($genre);

        return new JsonResponse($genre, JsonResponse::HTTP_CREATED);
    }

    #[Route('/genres/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        if (!$genre = $this->genreRepository->find($id)) {
            return new JsonResponse(['message' => 'No genre found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse($genre);
    }

    #[Route('/genres/{id}', name: 'update', methods: ['PATCH', 'PUT'])]
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

        if (!$genre = $this->genreRepository->find($id)) {
            return new JsonResponse(['message' => 'No genre found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $params = ['name' => $request->get('name')];

        if ($errorResponse = $this->genreValidator->validate($params, true)) {
            return $errorResponse;
        }

        $genre = $this->genreService->create($params, $genre);
        $this->genreService->save($genre);

        return new JsonResponse($genre, JsonResponse::HTTP_CREATED);
    }

    #[Route('/genres/{id}', name: 'destroy', methods: ['DELETE'])]
    public function destroy(int $id): JsonResponse
    {
        if (!$this->authService->authenticatedAsAdmin()) {
            return new JsonResponse(['message' => 'Insufficient access rights.'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        if (!$genre = $this->genreRepository->find($id)) {
            return new JsonResponse(['message' => 'No genre found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->genreService->delete($genre);

        return new JsonResponse(status: JsonResponse::HTTP_NO_CONTENT);
    }
}
