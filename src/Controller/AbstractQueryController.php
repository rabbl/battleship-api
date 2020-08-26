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

abstract class AbstractQueryController
{
    /** @var GameMaster $gameMaster */
    protected $gameMaster;

    public function __construct(GameMaster $gameMaster)
    {
        $this->gameMaster = $gameMaster;
    }
}
