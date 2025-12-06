<?php

class Fabricante
{
    private int $id;
    private string $nome;
    private string $site;

    /**
     * Getters
     */

    public function getId(): int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getSite(): string
    {
        return $this->site;
    }

    /**
     * Setters
     */

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function setSite(string $site): void
    {
        $this->site = $site;
    }
}
