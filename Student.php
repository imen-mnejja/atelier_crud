<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student
{
    #[ORM\Id]
    #[ORM\GeneratedValue]

    #[ORM\Column]
    private ?int $nsc = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\ManyToOne(inversedBy: 'students')]
    #[ORM\JoinColumn(name: "classe_Id", referencedColumnName: "id")]
    private ?Classroom $classe = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $creation_date = null;

    public function getnsc(): ?int
    {
        return $this->nsc;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getClasse(): ?Classroom
    {
        return $this->classe;
    }

    public function setClasse(?Classroom $classe): self
    {
        $this->classe = $classe;

        return $this;
    }

    public function getCreation_date(): ?\DateTimeImmutable
    {
        return $this->creation_date;
    }

    public function setCreation_date(\DateTimeImmutable $createdAt): self
    {
        $this->creation_date = $createdAt;

        return $this;
    }
}
