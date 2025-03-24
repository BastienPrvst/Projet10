<?php

namespace App\Entity;

use App\Repository\StatutRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatutRepository::class)]
class Statut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'statuts')]
    private ?Project $projectId = null;

    #[ORM\Column(length: 200)]
    private ?string $name = null;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'statut')]
    private Collection $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProjectId(): ?Project
    {
        return $this->projectId;
    }

    public function setProjectId(?Project $projectId): static
    {
        $this->projectId = $projectId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setStatut($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getStatut() === $this) {
                $task->setStatut(null);
            }
        }

        return $this;
    }
}
