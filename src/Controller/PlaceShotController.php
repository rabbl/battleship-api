<?php

declare(strict_types=1);

namespace App\Controller;

use App\Infrastructure\Request\PlaceShotRequest;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PlaceShotController extends AbstractCommandController
{
    /**
     * @Route("/{id}/shot", methods={"POST"})
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function placeShot(Request $request, string $id): Response
    {
        try {
            $this->assertRequestIsValid($request, __DIR__ . '/../Infrastructure/Schema/placeShotRequest.json');
        } catch (Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], 322);
        }

        $content = json_decode($request->getContent(), true);
        $placeShotRequest = PlaceShotRequest::fromValidatedContent($content);

        $this->gameMaster->humanPlacesShot(Uuid::fromString($id), $placeShotRequest->shot());
        $this->gameMaster->computerPlacesShot(Uuid::fromString($id));

        return new Response(null, Response::HTTP_CREATED);
    }
}
