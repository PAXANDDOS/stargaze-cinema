<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\LanguageRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'languages.')]
class LanguageController extends AbstractController
{
    public function __construct(
        private LanguageRepository $languageRepository
    ) {
    }

    #[Route('/languages', name: 'get', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $languages = $this->languageRepository->findAll();

        return new JsonResponse($languages);
    }

    #[Route('/languages/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        if (!$language = $this->languageRepository->find($id)) {
            return new JsonResponse(['message' => 'No language found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse($language);
    }
}
