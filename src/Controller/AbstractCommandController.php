<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\InvalidJsonException;
use App\Service\GameMaster;
use Swaggest\JsonSchema\Exception as SwaggestException;
use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\Schema;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractCommandController extends AbstractController
{
    /** @var GameMaster $gameMaster */
    protected $gameMaster;

    public function __construct(GameMaster $gameMaster)
    {
        $this->gameMaster = $gameMaster;
    }

    /**
     * @param Request $request
     * @param string $schemaFile
     * @throws SwaggestException
     * @throws InvalidValue
     */
    protected function assertRequestIsValid(Request $request, string $schemaFile): void
    {
        if (false === strpos($request->headers->get('Content-Type'), 'application/json')) {
            throw new InvalidJsonException('Expecting Header: Content-Type: application/json');
        }

        $schema = Schema::import($schemaFile);
        $schema->in(json_decode($request->getContent(), false));

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidJsonException('Invalid JSON received.');
        }
    }
}
