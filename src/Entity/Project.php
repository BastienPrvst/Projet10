<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private \DateTimeInterface $start_date;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $deadline = null;

    #[ORM\Column]
    private ?bool $archived = false;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\OneToMany(targetEntity: Tag::class, mappedBy: 'project')]
    private Collection $tags;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'projects')]
    private Collection $users;

    /**
     * @var Collection<int, Statut>
     */
    #[ORM\OneToMany(targetEntity: Statut::class, mappedBy: 'projectId')]
    private Collection $statuts;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'project', orphanRemoval: true)]
    private Collection $tasks;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->start_date = new \DateTime();
        $this->statuts = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getDeadline(): ?\DateTimeInterface
    {
        return $this->deadline;
    }

    public function setDeadline(\DateTimeInterface $deadline): static
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function isArchived(): ?bool
    {
        return $this->archived;
    }

    public function setArchived(bool $archived): static
    {
        $this->archived = $archived;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->setProject($this);
        }

        return $this;
    }


    public function removeTag(Tag $tag): static
    {
        // set the owning side to null (unless already changed)
        if ($this->tags->removeElement($tag) && $tag->getProject() === $this) {
            $tag->setProject(null);
        }

        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * @return Collection<int, Statut>
     */
    public function getStatuts(): Collection
    {
        return $this->statuts;
    }

    public function addStatut(Statut $statut): static
    {
        if (!$this->statuts->contains($statut)) {
            $this->statuts->add($statut);
            $statut->setProjectId($this);
        }

        return $this;
    }

    public function removeStatut(Statut $statut): static
    {
        if ($this->statuts->removeElement($statut)) {
            // set the owning side to null (unless already changed)
            if ($statut->getProjectId() === $this) {
                $statut->setProjectId(null);
            }
        }

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
            $task->setProject($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getProject() === $this) {
                $task->setProject(null);
            }
        }

        return $this;
    }

}
