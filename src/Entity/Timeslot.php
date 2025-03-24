<?php

namespace App\Entity;

use App\Repository\TimeslotRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimeslotRepository::class)]
class Timeslot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'timeslot', cascade: ['persist', 'remove'])]
    private ?Task $taskId = null;

    #[ORM\ManyToOne(inversedBy: 'timeslots')]
    private ?User $userId = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startSlot = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endSlot = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaskId(): ?Task
    {
        return $this->taskId;
    }

    public function setTaskId(?Task $taskId): static
    {
        $this->taskId = $taskId;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getStartSlot(): ?\DateTimeInterface
    {
        return $this->startSlot;
    }

    public function setStartSlot(\DateTimeInterface $startSlot): static
    {
        $this->startSlot = $startSlot;

        return $this;
    }

    public function getEndSlot(): ?\DateTimeInterface
    {
        return $this->endSlot;
    }

    public function setEndSlot(\DateTimeInterface $endSlot): static
    {
        $this->endSlot = $endSlot;

        return $this;
    }
}
