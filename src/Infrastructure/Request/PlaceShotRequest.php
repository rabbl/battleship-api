<?php

declare(strict_types=1);

namespace App\Infrastructure\Request;

use App\Model\Hole;
use App\Model\Shot;

class PlaceShotRequest
{

    private $letter;
    private $number;

    public static function fromValidatedContent(array $content): PlaceShotRequest
    {
        $letter = $content['letter'];
        $number = $content['number'];
        return new self($letter, $number);
    }

    private function __construct(string $letter, int $number)
    {
        $this->letter = $letter;
        $this->number = $number;
    }

    public function letter(): string
    {
        return $this->letter;
    }

    public function number(): int
    {
        return $this->number;
    }

    public function hole(): Hole
    {
        return Hole::createFromLetterAndNumber($this->letter(), $this->number());
    }

    public function shot(): Shot
    {
        return new Shot($this->hole());
    }
}
