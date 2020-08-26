<?php

declare(strict_types=1);

namespace App\Controller;

use App\Infrastructure\Request\StartNewGameRequest;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class StartNewGameController extends AbstractCommandController
{
    /**
     * @param Request $request
     * @Route("/", methods={"POST"})
     * @return Response
     */
    public function startNewGame(Request $request): Response
    {
        try {
            $this->assertRequestIsValid($request, __DIR__ . '/../Infrastructure/Schema/startNewGameRequest.json');
        } catch (Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], 322);
        }

        $content = json_decode($request->getContent(), true);
        $startNewGameRequest = StartNewGameRequest::fromValidatedContent($content);

        if ($this->gameMaster->gameIdExists($startNewGameRequest->id())) {
            return new JsonResponse(
                ['message' => sprintf("GameId: '%s' already exists", $startNewGameRequest->id()->toString())],
                Response::HTTP_CONFLICT
            );
        }

        $this->gameMaster->createGame(
            $startNewGameRequest->id(),
            $startNewGameRequest->human(),
            $startNewGameRequest->computer()
        );

        return new Response(null, Response::HTTP_CREATED);
    }
}
