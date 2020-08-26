<?php

declare(strict_types=1);

namespace App\Infrastructure\Request;

use App\Model\Strategy\StrategyFactory;
use App\Model\Player\Player;
use App\Model\Ship\PlacedShip;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class StartNewGameRequest
{

    private $id;
    private $name;
    private $strategyId;
    private $placedShips;

    public static function fromValidatedContent(array $content): StartNewGameRequest
    {
        $id = Uuid::fromString($content['id']);
        $name = $content['name'];
        $strategyId = $content['strategy'] ?? 1;
        $strategy = StrategyFactory::build($strategyId);

        $placedShips = [];
        if (isset($content['ships']) && is_array($content['ships'])) {
            foreach ($content['ships'] as $shipArr) {
                $placedShips[] = PlacedShip::fromArray($shipArr);
            }
        }

        if (count($placedShips) === 0) {
            $placedShips = $strategy::createGridWithShips()->placedShips();
        }

        return new self($id, $name, $strategyId, $placedShips);
    }

    private function __construct(UuidInterface $id, string $name, int $strategyId, array $placedShips)
    {
        $this->id = $id;
        $this->name = $name;
        $this->strategyId = $strategyId;
        $this->placedShips = $placedShips;
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function strategyId(): int
    {
        return $this->strategyId;
    }

    public function placedShips(): array
    {
        return $this->placedShips;
    }

    public function human(): Player
    {
        return new Player($this->name(), $this->placedShips(), [], $this->strategyId());
    }

    public function computer(): Player
    {
        $placedShips = StrategyFactory::build($this->strategyId)::createGridWithShips()->placedShips();
        return new Player('Computer', $placedShips, [], $this->strategyId());
    }
}
