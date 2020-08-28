<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Game;
use App\Infrastructure\Response\GameDebugResponse;
use App\Infrastructure\Response\GameStatusResponse;
use App\Infrastructure\Response\ShotStatusResponse;
use App\Model\Grid;
use App\Model\Hole;
use App\Model\Shot;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GameStatusController extends AbstractQueryController
{
    /**
     *
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function gameStatus(Request $request, string $id): Response
    {
        $game = $this->gameMaster->loadGame(Uuid::fromString($id));
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            if (!$game instanceof Game) {
                return new JsonResponse(['message' => 'Not found'], Response::HTTP_NOT_FOUND);
            }

            return new JsonResponse(new GameStatusResponse($game), Response::HTTP_OK);
        }

        return $this->render('game.html.twig', [
            'game' => $game,
            'gameStatus' => new GameStatusResponse($game),
            'letters' => Grid::letters(),
            'numbers' => Grid::numbers()
        ]);
    }

    /**
     * @Route("/{id}/shot/{letter}/{number}", methods={"GET"})
     * @param string $id
     * @param string $letter
     * @param string $number
     * @return Response
     */
    public function shotStatus(string $id, string $letter, string $number): Response
    {
        $game = $this->gameMaster->loadGame(Uuid::fromString($id));
        if (!$game instanceof Game) {
            return new JsonResponse(['message' => 'Not found'], Response::HTTP_NOT_FOUND);
        }

        $shotStatusResponse = new ShotStatusResponse($game, new Shot(Hole::createFromLetterAndNumber($letter, (int)$number)));

        return new JsonResponse($shotStatusResponse, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}/debug", methods={"GET"})
     * @param string $id
     * @return Response
     */
    public function gameDebug(string $id): Response
    {
        $game = $this->gameMaster->loadGame(Uuid::fromString($id));
        if (!$game instanceof Game) {
            return new JsonResponse(['message' => 'Not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(new GameDebugResponse($game), Response::HTTP_OK);
    }
}
