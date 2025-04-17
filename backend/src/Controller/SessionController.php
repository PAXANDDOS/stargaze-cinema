<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\SessionRepository;
use App\Service\AuthService;
use App\Service\SessionService;
use App\Validator\SessionValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'sessions.')]
class SessionController extends AbstractController
{
    public function __construct(
        private AuthService $authService,
        private SessionService $sessionService,
        private SessionValidator $sessionValidator,
        private SessionRepository $sessionRepository
    ) {
    }

    #[Route('/sessions', name: 'get', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $sessions = $this->sessionRepository->findAll();

        return new JsonResponse($sessions);
    }

    #[Route('/sessions', name: 'store', methods: ['POST'])]
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
            'beginAt' => $request->get('begin_at'),
            'endAt' => $request->get('end_at'),
            'movieId' => (int) $request->get('movie_id') ?: null,
            'hallId' => (int) $request->get('hall_id') ?: null,
        ];

        if ($errorResponse = $this->sessionValidator->validate($params)) {
            return $errorResponse;
        }

        $params['beginAt'] = new \DateTime($params['beginAt']);
        $params['endAt'] = new \DateTime($params['endAt']);

        $session = $this->sessionService->create($params);

        if ($session->getBeginAt() > $session->getEndAt()) {
            return new JsonResponse(
                ['message' => 'Start of the session should be earlier than the end.'],
                JsonResponse::HTTP_CONFLICT
            );
        }

        $minutes = ceil(abs($session->getEndAt()->getTimestamp() - $session->getBeginAt()->getTimestamp()) / 60);
        if ($minutes < $session->getMovie()->getRuntime()) {
            return new JsonResponse(
                ['message' => 'Session can not be shorter then the movie.'],
                JsonResponse::HTTP_CONFLICT
            );
        }

        $this->sessionService->save($session);

        return new JsonResponse($session, JsonResponse::HTTP_CREATED);
    }

    #[Route('/sessions/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        if (!$session = $this->sessionRepository->find($id)) {
            return new JsonResponse(['message' => 'No session found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse($session);
    }

    #[Route('/sessions/{id}', name: 'update', methods: ['PATCH', 'PUT'])]
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

        if (!$session = $this->sessionRepository->find($id)) {
            return new JsonResponse(['message' => 'No session found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $params = [
            'beginAt' => $request->get('begin_at'),
            'endAt' => $request->get('end_at'),
            'movieId' => (int) $request->get('movie_id') ?: null,
            'hallId' => (int) $request->get('hall_id') ?: null,
        ];

        if ($errorResponse = $this->sessionValidator->validate($params, true)) {
            return $errorResponse;
        }

        if (isset($params['beginAt'])) {
            $params['beginAt'] = new \DateTime($params['beginAt']);
        }
        if (isset($params['endAt'])) {
            $params['endAt'] = new \DateTime($params['endAt']);
        }

        $session = $this->sessionService->create($params, $session);

        if ($session->getBeginAt() > $session->getEndAt()) {
            return new JsonResponse(
                ['message' => 'Start of the session should be earlier than the end.'],
                JsonResponse::HTTP_CONFLICT
            );
        }

        $minutes = ceil(abs($session->getEndAt()->getTimestamp() - $session->getBeginAt()->getTimestamp()) / 60);
        if ($minutes < $session->getMovie()->getRuntime()) {
            return new JsonResponse(
                ['message' => 'Session can not be shorter then the movie.'],
                JsonResponse::HTTP_CONFLICT
            );
        }

        $this->sessionService->save($session);

        return new JsonResponse($session, JsonResponse::HTTP_CREATED);
    }

    #[Route('/sessions/{id}', name: 'destroy', methods: ['DELETE'])]
    public function destroy(int $id): JsonResponse
    {
        if (!$this->authService->authenticatedAsAdmin()) {
            return new JsonResponse(['message' => 'Insufficient access rights.'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        if (!$session = $this->sessionRepository->find($id)) {
            return new JsonResponse(['message' => 'No session found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->sessionService->delete($session);

        return new JsonResponse(status: JsonResponse::HTTP_NO_CONTENT);
    }
}
