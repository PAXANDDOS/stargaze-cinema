<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CountryRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'countries.')]
class CountryController extends AbstractController
{
    public function __construct(
        private CountryRepository $countryRepository
    ) {
    }

    #[Route('/countries', name: 'get', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $countries = $this->countryRepository->findAll();

        return new JsonResponse($countries);
    }

    #[Route('/countries/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        if (!$country = $this->countryRepository->find($id)) {
            return new JsonResponse(['message' => 'No country found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse($country);
    }
}
