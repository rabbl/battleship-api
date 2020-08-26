<?php

declare(strict_types=1);

namespace App\Entity;

use App\Model\Player\Player;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private $id;

    /**
     * @var array
     * @ORM\Column(type="array")
     */
    private $human;

    /**
     * @var array
     * @ORM\Column(type="array")
     */
    private $computer;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public static function createNew(UuidInterface $id, Player $human, Player $computer): Game
    {
        return new self($id, $human, $computer);
    }

    private function __construct(UuidInterface $id, Player $human, Player $computer)
    {
        $this->id = $id;
        $this->setHuman($human);
        $this->setComputer($computer);
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function human(): Player
    {
        return Player::fromArray($this->human);
    }

    public function setHuman(Player $human): void
    {
        $this->human = $human->toArray();
        $this->updatedAt = new DateTime();
    }

    /**
     * @return Player
     */
    public function computer(): Player
    {
        return Player::fromArray($this->computer);
    }

    public function setComputer(Player $computer): void
    {
        $this->computer = $computer->toArray();
        $this->updatedAt = new DateTime();
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTime
    {
        return $this->updatedAt;
    }
}
