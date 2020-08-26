<?php

declare(strict_types=1);

namespace App\Model;

class Shot
{
    protected $hole;

    public static function fromArray($arr): Shot
    {
        $hole = Hole::createFromArray($arr['hole']);
        return new self($hole);
    }

    public function __construct(Hole $hole)
    {
        $this->hole = $hole;
    }

    public function hole(): Hole
    {
        return $this->hole;
    }

    public function toArray(): array
    {
        return [
            'hole' => $this->hole()->toArray()
        ];
    }
}
