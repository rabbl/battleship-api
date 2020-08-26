<?php

declare(strict_types=1);

namespace App\Model;

use JsonSerializable;

class ShotResult extends Shot implements JsonSerializable
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

    public function jsonSerialize()
    {
        return [
            'letter' => $this->hole()->letter(),
            'number' => $this->hole()->number(),
            'result' => $this->result(),
            'shipId' => $this->shipId()
        ];
    }
}
