<?php

declare(strict_types=1);

namespace App\Controller;

use App\Message\EmailNotification;
use App\Repository\TicketRepository;
use App\Service\AuthService;
use App\Service\TicketService;
use App\Validator\TicketValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'tickets.')]
class TicketController extends AbstractController
{
    public function __construct(
        private AuthService $authService,
        private TicketService $ticketService,
        private TicketValidator $ticketValidator,
        private TicketRepository $ticketRepository
    ) {
    }

    #[Route('/tickets', name: 'get', methods: ['GET'])]
    public function index(): JsonResponse
    {
        if (!$this->authService->authenticatedAsAdmin()) {
            return new JsonResponse(['message' => 'Insufficient access rights.'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $tickets = $this->ticketRepository->findAll();

        return new JsonResponse($tickets);
    }

    #[Route('/tickets', name: 'store', methods: ['POST'])]
    public function store(Request $request, MessageBusInterface $bus): JsonResponse
    {
        if (!$this->authService->isAuthenticated()) {
            return new JsonResponse(['message' => 'Unauthorized.'], JsonResponse::HTTP_UNAUTHORIZED);
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
            'place' => (int) $request->get('place') ?: null,
            'userId' => (int) $request->get('user_id') ?: $this->authService->getCurrentUser()->getId(),
            'sessionId' => (int) $request->get('session_id') ?: null,
        ];

        if ($errorResponse = $this->ticketValidator->validate($params)) {
            return $errorResponse;
        }

        $ticket = $this->ticketService->create($params);
        $user = $ticket->getUser();
        $session = $ticket->getSession();
        $movie = $session->getMovie();
        $hall = $session->getHall();

        if (1 > $ticket->getPlace() || $ticket->getPlace() > $hall->getCapacity()) {
            return new JsonResponse(['message' => 'Selected place is out of range.'], JsonResponse::HTTP_CONFLICT);
        }

        if ($session->getEndAt() < new \DateTime('now')) {
            return new JsonResponse(['message' => 'Selected session has expired.'], JsonResponse::HTTP_CONFLICT);
        }

        if (
            $this->ticketRepository->findOneBy([
                'place' => $ticket->getPlace(),
                'session' => $ticket->getSession(),
            ])
        ) {
            return new JsonResponse(['message' => 'This place is already taken.'], JsonResponse::HTTP_CONFLICT);
        }

        $bus->dispatch(new EmailNotification(
            to: [$user->getEmail(), $user->getName()],
            subject: 'Your ticket for '.$movie->getTitle(),
            template: 'emails/ticket.html.twig',
            context: [
                'place' => $ticket->getPlace(),
                'user' => $user,
                'hall' => $hall,
                'movie' => $movie,
                'beginAt' => $session->getBeginAt()->format('l d at G:i'),
                'endAt' => $session->getEndAt()->format('l d at G:i'),
            ]
        ));

        $this->ticketService->save($ticket);

        return new JsonResponse($ticket, JsonResponse::HTTP_CREATED);
    }

    #[Route('/tickets/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        if (!$ticket = $this->ticketRepository->find($id)) {
            return new JsonResponse(['message' => 'No ticket found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse($ticket);
    }

    #[Route('/tickets/{id}', name: 'update', methods: ['PATCH', 'PUT'])]
    public function update(Request $request, int $id, MessageBusInterface $bus): JsonResponse
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

        if (!$ticket = $this->ticketRepository->find($id)) {
            return new JsonResponse(['message' => 'No ticket found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $params = [
            'place' => (int) $request->get('place') ?: null,
            'userId' => (int) $request->get('user_id') ?: null,
            'sessionId' => (int) $request->get('session_id') ?: null,
        ];

        if ($errorResponse = $this->ticketValidator->validate($params, true)) {
            return $errorResponse;
        }

        $ticket = $this->ticketService->create($params, $ticket);
        $user = $ticket->getUser();
        $session = $ticket->getSession();
        $movie = $session->getMovie();
        $hall = $session->getHall();

        if (1 > $ticket->getPlace() || $ticket->getPlace() > $hall->getCapacity()) {
            return new JsonResponse(['message' => 'Selected place is out of range.'], JsonResponse::HTTP_CONFLICT);
        }

        if ($session->getEndAt() < new \DateTime('now')) {
            return new JsonResponse(['message' => 'Selected session has expired.'], JsonResponse::HTTP_CONFLICT);
        }

        if (
            $this->ticketRepository->findOneBy([
                'place' => $ticket->getPlace(),
                'session' => $ticket->getSession(),
            ])
        ) {
            return new JsonResponse(['message' => 'This place is already taken.'], JsonResponse::HTTP_CONFLICT);
        }

        $bus->dispatch(new EmailNotification(
            to: [$user->getEmail(), $user->getName()],
            subject: '[UPDATED] Your ticket for '.$movie->getTitle(),
            template: 'emails/ticket.html.twig',
            context: [
                'place' => $ticket->getPlace(),
                'user' => $user,
                'hall' => $hall,
                'movie' => $movie,
                'beginAt' => $session->getBeginAt()->format('l d at G:i'),
                'endAt' => $session->getEndAt()->format('l d at G:i'),
            ]
        ));

        $this->ticketService->save($ticket);

        return new JsonResponse($ticket, JsonResponse::HTTP_CREATED);
    }

    #[Route('/tickets/{id}', name: 'destroy', methods: ['DELETE'])]
    public function destroy(int $id): JsonResponse
    {
        if (!$this->authService->authenticatedAsAdmin()) {
            return new JsonResponse(['message' => 'Insufficient access rights.'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        if (!$ticket = $this->ticketRepository->find($id)) {
            return new JsonResponse(['message' => 'No ticket found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->ticketService->delete($ticket);

        return new JsonResponse(status: JsonResponse::HTTP_NO_CONTENT);
    }
}
