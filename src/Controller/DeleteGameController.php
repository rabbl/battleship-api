<?php

declare(strict_types=1);

namespace App\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DeleteGameController extends AbstractCommandController
{
    /**
     * @Route("/{id}", methods={"DELETE"})
     * @param string $id
     * @return Response
     */
    public function deleteGame(string $id): Response
    {
        $this->gameMaster->deleteGame(Uuid::fromString($id));
        return new Response(null, Response::HTTP_CREATED);
    }
}
