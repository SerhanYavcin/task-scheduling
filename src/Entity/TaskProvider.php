<?php

namespace App\Entity;

use App\Repository\TaskProviderRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TaskProviderRepository::class)
 * @ORM\Table(name="task_provider")
 */
class TaskProvider
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer",)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=4, unique=true)
     * @Assert\NotBlank()
     */
    private $code;

    /**
     * @ORM\Column(type="string", name="id_key", length=255)
     * @Assert\NotBlank()
     */
    private $idKey;

    /**
     * @ORM\Column(type="string", name="duration_key", length=255)
     * @Assert\NotBlank()
     */
    private $durationKey;

    /**
     * @ORM\Column(type="string", name="difficulty_key", length=255)
     * @Assert\NotBlank()
     */
    private $difficultyKey;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getIdKey(): ?string
    {
        return $this->idKey;
    }

    public function setIdKey(string $idKey): self
    {
        $this->idKey = $idKey;

        return $this;
    }

    public function getDurationKey(): ?string
    {
        return $this->durationKey;
    }

    public function setDurationKey(string $durationKey): self
    {
        $this->durationKey = $durationKey;

        return $this;
    }

    public function getDifficultyKey(): ?string
    {
        return $this->difficultyKey;
    }

    public function setDifficultyKey(string $difficultyKey): self
    {
        $this->difficultyKey = $difficultyKey;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }
}
