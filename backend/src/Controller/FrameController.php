<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\FrameRepository;
use App\Service\AuthService;
use App\Service\FrameService;
use App\Service\S3Service;
use App\Validator\FrameValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'frames.')]
class FrameController extends AbstractController
{
    public function __construct(
        private S3Service $s3Service,
        private AuthService $authService,
        private FrameService $frameService,
        private FrameValidator $frameValidator,
        private FrameRepository $frameRepository
    ) {
    }

    #[Route('/frames', name: 'get', methods: ['GET'])]
    public function index(): JsonResponse
    {
        if (!$this->authService->authenticatedAsAdmin()) {
            return new JsonResponse(['message' => 'Insufficient access rights.'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $frames = $this->frameRepository->findAll();

        return new JsonResponse($frames);
    }

    #[Route('/frames', name: 'store', methods: ['POST'])]
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
            'movieId' => (int) $request->get('movie_id') ?: null,
            'image' => $request->get('image'),
        ];

        if ($image = $request->files->get('image')) {
            $params['image'] = $this->s3Service->upload($image, 'frames', $params['movieId'].'_'.md5(uniqid()));
        }

        if ($errorResponse = $this->frameValidator->validate($params)) {
            return $errorResponse;
        }

        $frame = $this->frameService->create($params);
        $this->frameService->save($frame);

        return new JsonResponse($frame, JsonResponse::HTTP_CREATED);
    }

    #[Route('/frames/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        if (!$frame = $this->frameRepository->find($id)) {
            return new JsonResponse(['message' => 'No frame found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse($frame);
    }

    #[Route('/frames/{id}', name: 'destroy', methods: ['DELETE'])]
    public function destroy(int $id): JsonResponse
    {
        if (!$this->authService->authenticatedAsAdmin()) {
            return new JsonResponse(['message' => 'Insufficient access rights.'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        if (!$frame = $this->frameRepository->find($id)) {
            return new JsonResponse(['message' => 'No frame found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->frameService->delete($frame);

        return new JsonResponse(status: JsonResponse::HTTP_NO_CONTENT);
    }
}
