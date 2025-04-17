<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\MovieRepository;
use App\Service\AuthService;
use App\Service\FrameService;
use App\Service\MovieService;
use App\Service\S3Service;
use App\Validator\MovieValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'movies.')]
class MovieController extends AbstractController
{
    public function __construct(
        private AuthService $authService,
        private MovieService $movieService,
        private FrameService $frameService,
        private MovieRepository $movieRepository,
        private MovieValidator $movieValidator,
        private S3Service $s3Service
    ) {
    }

    #[Route('/movies', name: 'get', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $movies = $this->movieRepository->findAllWithQueryPaginate($request->query->all());

        return new JsonResponse($movies);
    }

    #[Route('/movies', name: 'store', methods: ['POST'])]
    public function store(Request $request): JsonResponse
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

        $params = [
            'title' => $request->get('title'),
            'synopsis' => $request->get('synopsis'),
            'price' => (float) $request->get('price') ?: null,
            'year' => (int) $request->get('year') ?: null,
            'runtime' => (int) $request->get('runtime') ?: null,
            'rating' => $request->get('rating'),
            'languageId' => (int) $request->get('language_id') ?: null,
            'countryIds' => $request->get('country_ids') ? json_decode($request->get('country_ids')) : null,
            'genreIds' => $request->get('genre_ids') ? json_decode($request->get('genre_ids')) : null,
            'directorIds' => $request->get('director_ids') ? json_decode($request->get('director_ids')) : null,
        ];

        if ($posterFile = $request->files->get('poster')) {
            $params['poster'] = $this->s3Service->upload($posterFile, 'posters');
        }

        if ($errorResponse = $this->movieValidator->validate($params)) {
            return $errorResponse;
        }

        $movie = $this->movieService->create($params);
        $this->movieService->save($movie);

        return new JsonResponse($movie, JsonResponse::HTTP_CREATED);
    }

    #[Route('/movies/{id}', name: 'show', methods: ['GET'])]
    public function show(string $id): JsonResponse
    {
        if (ctype_digit($id)) {
            $movie = $this->movieRepository->find($id);
        } else {
            $movie = $this->movieRepository->findOneBy(['title' => $id]);
        }

        if (!$movie) {
            return new JsonResponse(['message' => 'No movie found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse($movie);
    }

    #[Route('/movies/{id}', name: 'update', methods: ['PATCH', 'PUT'])]
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

        if (!$movie = $this->movieRepository->find($id)) {
            return new JsonResponse(['message' => 'No movie found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $params = [
            'title' => $request->get('title'),
            'synopsis' => $request->get('synopsis'),
            'price' => (float) $request->get('price') ?: null,
            'year' => (int) $request->get('year') ?: null,
            'runtime' => (int) $request->get('runtime') ?: null,
            'rating' => $request->get('rating'),
            'languageId' => (int) $request->get('language_id') ?: null,
            'countryIds' => $request->get('country_ids') ? json_decode($request->get('country_ids')) : null,
            'genreIds' => $request->get('genre_ids') ? json_decode($request->get('genre_ids')) : null,
            'directorIds' => $request->get('director_ids') ? json_decode($request->get('director_ids')) : null,
        ];

        if ($posterFile = $request->files->get('poster')) {
            $params['poster'] = $this->s3Service->upload($posterFile, 'posters');
        } else {
            $params['poster'] = $movie->getPoster();
        }

        if ($errorResponse = $this->movieValidator->validate($params, true)) {
            return $errorResponse;
        }

        $movie = $this->movieService->create($params, $movie);
        $this->movieService->save($movie);

        return new JsonResponse($movie, JsonResponse::HTTP_CREATED);
    }

    #[Route('/movies/{id}', name: 'destroy', methods: ['DELETE'])]
    public function destroy(int $id): JsonResponse
    {
        if (!$this->authService->authenticatedAsAdmin()) {
            return new JsonResponse(['message' => 'Insufficient access rights.'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        if (!$movie = $this->movieRepository->find($id)) {
            return new JsonResponse(['message' => 'No movie found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        if ($poster = $movie->getPoster()) {
            $bucket = $this->s3Service->getBucket();
            $this->s3Service->delete(explode("$bucket/", $poster)[1]);
        }
        $this->movieService->delete($movie);

        return new JsonResponse(status: JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route('/movies/{id}/frames', name: 'uploadFrames', methods: ['POST'])]
    public function uploadFrames(int $id, Request $request): JsonResponse
    {
        if ('json' === $request->getContentType()) {
            return new JsonResponse(
                ['message' => 'JSON request unsupported. Use Form-Data instead.'],
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!$this->authService->authenticatedAsAdmin()) {
            return new JsonResponse(['message' => 'Insufficient access rights.'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        if (!$movie = $this->movieRepository->find($id)) {
            return new JsonResponse(['message' => 'No movie found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $frames = [];

        foreach ($request->files as $file) {
            if (is_string($file)) {
                $imageUrl = $file;
                continue;
            }

            $imageUrl = $this->s3Service->upload($file, 'frames', $movie->getId().'_'.md5(uniqid()));

            $params = [
                'movieId' => $id,
                'image' => $imageUrl,
            ];

            $frame = $this->frameService->create($params);
            $this->frameService->save($frame);

            $frames[] = $imageUrl;
        }

        return new JsonResponse(
            ['message' => 'Frames were successfully uploaded.', 'frames' => $frames],
            JsonResponse::HTTP_CREATED
        );
    }
}
