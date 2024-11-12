<?php

namespace App\Entity;

use App\Repository\CepRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CepRepository::class)]
class Cep
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 8)]
    private ?string $cep = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logradouro = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $bairro = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $localidade = null;

    #[ORM\Column(length: 2, nullable: true)]
    private ?string $uf = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCep(): ?string
    {
        return $this->cep;
    }

    public function setCep(string $cep): static
    {
        $this->cep = $cep;

        return $this;
    }

    public function getLogradouro(): ?string
    {
        return $this->logradouro;
    }

    public function setLogradouro(?string $logradouro): static
    {
        $this->logradouro = $logradouro;

        return $this;
    }

    public function getBairro(): ?string
    {
        return $this->bairro;
    }

    public function setBairro(?string $bairro): static
    {
        $this->bairro = $bairro;

        return $this;
    }

    public function getLocalidade(): ?string
    {
        return $this->localidade;
    }

    public function setLocalidade(?string $localidade): static
    {
        $this->localidade = $localidade;

        return $this;
    }

    public function getUf(): ?string
    {
        return $this->uf;
    }

    public function setUf(?string $uf): static
    {
        $this->uf = $uf;

        return $this;
    }
}
