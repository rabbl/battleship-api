<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\GameMaster;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractQueryController extends AbstractController
{
    protected GameMaster $gameMaster;

    public function __construct(GameMaster $gameMaster)
    {
        $this->gameMaster = $gameMaster;
    }
}
