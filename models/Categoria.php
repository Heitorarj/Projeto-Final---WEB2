<?php

class Categoria
{
    private int $id;
    private string $nome;

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
}
