<?php

declare(strict_types=1);

namespace App\Model;

class ShotResult extends Shot
{
    protected $hole;
    private $result;
    private $shipId;

    public function __construct(Hole $hole, int $result, int $shipId = 0)
    {
        parent::__construct($hole);
        $this->result = $result;
        $this->shipId = $shipId;
    }

    public function hole(): Hole
    {
        return $this->hole;
    }

    public function result(): int
    {
        return $this->result;
    }

    public function shipId(): int
    {
        return $this->shipId;
    }
}
